var myapp = angular.module('my_app', ['datatables']);
myapp.controller('users', function ($scope, $http) {
    $scope.master = {};
    $scope.usersInformation = function () {
        $http({
            method: 'GET',
            url: 'organization_ajax.php?action=organization_list'
        })
            .then(function (success) {
                $scope.users_list = [];
                $scope.users_list = success.data;
            }, function (error) {
                console.log(error);
            });
    };
    $scope.addModal = function () {
        $scope.users_form = angular.copy($scope.master);
        $scope.form_name = 'Add Organization';
        $("#organization_form_id #action_text").val('insert');
        $('#form_modal').modal('show');
    };

    $scope.EditModal = function (user) {
        loadDistict(user.state, user.district);
        $scope.form_name = 'Edit Organization';
        var edit_form = {};
        angular.copy(user, edit_form);
        $scope.users_form = edit_form;
        if (user.district != '') {
            $("#organization_form_id #action_text").val('update');
            $("#organization_form_id #state").val(user.state);
        }
        $('#form_modal').modal('show');
    };

    $scope.UserAddUpdate = function (users_form) {
        var users_information = users_form;
        var action_value = $("#organization_form_id #action_text").val();
        var state_id = $("#organization_form_id #state").val();
        var district_id = $("#organization_form_id #district_distict").val();
        $http({
            method: 'POST',
            url: 'organization_ajax.php?action=' + action_value + '&state_id=' + state_id + '&district_id=' + district_id,
            data: users_information,
        }).then(function (response) {
            $scope.usersInformation();
            $scope.success_msg = response.data;
        }, function (error) {
            console.log(error);
        });
        $('#form_modal').modal('hide');
    };

    $scope.DeleteModal = function (user) {
        var r = confirm("Are you sure want to delete ?");
        if (r == true) {
            var users_record_id = user.org_id;
            $http({
                method: 'POST',
                url: 'organization_ajax.php?action=delete',
                data: users_record_id,
            }).then(function (response) {
                var index = $scope.users_list.indexOf(user);
                $scope.users_list.splice(index, 1);
                $scope.success_msg = response.data;
            }, function (error) {
                console.log(error);
            });
        }
    };

    $scope.loadstate = function (statew_ss) {
        $http({
            method: 'GET',
            url: 'load_state.php'
        }).then(function (success) {
            $scope.state_list = [];
            $scope.state_list = success.data;
        }, function (error) {
            console.log(error);
        });
    };
});

