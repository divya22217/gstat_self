var myapp = angular.module('my_app', ['datatables']);
myapp.controller('submenus', function ($scope, $http) {
    $scope.master = {};
    $scope.submenuInformation = function () {
        $http({
            method: 'GET',
            url: 'submenu_ajax.php?action=submenu_list'
        })
            .then(function (success) {
                $scope.submenu_list = [];
                $scope.submenu_list = success.data;
            }, function (error) {
                console.log(error);
            });
    };


    $scope.EditModal = function (submenu) {
        $scope.form_name = 'Edit Menu';
        var edit_form = {};
        angular.copy(submenu, edit_form);
        $scope.submenus_form = edit_form;
        $('#form_modal').modal('show');
    };

    $scope.MenuAddUpdate = function (submenus_form) {
        var submenus_information = submenus_form;
        var action_value = $("#submenus_form_id #action_text").val();
        $http({
            method: 'POST',
            url: 'submenu_ajax.php?action=' + action_value,
            data: submenus_information,
        }).then(function (response) {
            $scope.submenuInformation();
            $scope.success_msg = response.data;
        }, function (error) {
            console.log(error);
        });
        $('#form_modal').modal('hide');
    };

    $scope.DeleteModal = function (submenu) {
        var r = confirm("Are you sure want to delete ?");
        if (r == true) {
            var submenus_record_id = submenu.submenu_id;
            $http({
                method: 'POST',
                url: 'submenu_ajax.php?action=delete',
                data: submenus_record_id,
            }).then(function (response) {
               /*  var index = $scope.users_list.indexOf(user);
                $scope.users_list.splice(index, 1); */
                $scope.success_msg = response.data;
				$scope.submenuInformation();
            }, function (error) {
                console.log(error);
            });
        }
    };
	
	$scope.RestoreModal = function (submenu) {
        var r = confirm("Are you sure want to Restore ?");
        if (r == true) {
            var submenus_record_id = submenu.submenu_id;
            $http({
                method: 'POST',
                url: 'submenu_ajax.php?action=restore',
                data: submenus_record_id,
            }).then(function (response) {
               /*  var index = $scope.users_list.indexOf(user);
                $scope.users_list.splice(index, 1); */
                $scope.success_msg = response.data;
				$scope.submenuInformation();
            }, function (error) {
                console.log(error);
            });
        }
    };


});

