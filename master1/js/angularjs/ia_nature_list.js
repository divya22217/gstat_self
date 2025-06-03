var myapp = angular.module('my_app', ['datatables']);
myapp.controller('users', function ($scope, $http) {
    $scope.master = {};
    $scope.natureInformation = function () {
        $http({
            method: 'GET',
            url: 'ia_nature_ajax.php?action=ia_nature_list'
        }).then(function (success) {
            $scope.users_list = [];
            $scope.users_list = success.data;
        }, function (error) {
            console.log(error);
        });
    };
    $scope.addModal = function () {
        $scope.users_form = angular.copy($scope.master);
        $scope.form_name = 'Add IA Nature';
        $("#ia_nature_form_id #action_text").val('insert');
        $('#form_modal').modal('show');
    };
    $scope.UserAddUpdate = function (users_form) {
        var users_information = users_form;
        var action_value = $("#ia_nature_form_id #action_text").val();
        $http({
            method: 'POST',
            url: 'ia_nature_ajax.php?action=' + action_value,
            data: users_information,
        }).then(function (response) {
            $scope.natureInformation();
            $scope.success_msg = response.data;
        }, function (error) {
            console.log(error);
        });
        $('#form_modal').modal('hide');
    };
    $scope.EditModal = function (user) {
        $scope.form_name = 'Edit IA Nature';
        var edit_form = {};
        angular.copy(user, edit_form);
        $scope.users_form = edit_form;
        $("#ia_nature_form_id #action_text").val('update');
        $('#form_modal').modal('show');
    };

    $scope.DeleteModal = function (user) {
        var r = confirm("Are you sure want to delete ?");
        if (r == true) {
            var users_record_id = user.nature_code;
            $http({
                method: 'POST',
                url: 'ia_nature_ajax.php?action=delete',
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


});