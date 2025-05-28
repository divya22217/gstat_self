var myapp = angular.module('my_app', ['datatables']);
myapp.controller('menus', function ($scope, $http) {
    $scope.master = {};
    $scope.menuInformation = function () {
        $http({
            method: 'GET',
            url: 'menu_ajax.php?action=menu_list'
        })
            .then(function (success) {
                $scope.menu_list = [];
                $scope.menu_list = success.data;
            }, function (error) {
                console.log(error);
            });
    };


    $scope.EditModal = function (menu) {
        $scope.form_name = 'Edit Menu';
        var edit_form = {};
        angular.copy(menu, edit_form);
        $scope.menus_form = edit_form;
        $('#form_modal').modal('show');
    };

    $scope.MenuAddUpdate = function (menus_form) {
        var menus_information = menus_form;
        var action_value = $("#menus_form_id #action_text").val();
        $http({
            method: 'POST',
            url: 'menu_ajax.php?action=' + action_value,
            data: menus_information,
        }).then(function (response) {
            $scope.menuInformation();
            $scope.success_msg = response.data;
        }, function (error) {
            console.log(error);
        });
        $('#form_modal').modal('hide');
    };

    $scope.DeleteModal = function (menu) {
        var r = confirm("Are you sure want to delete ?");
        if (r == true) {
            var menus_record_id = menu.menu_id;
            $http({
                method: 'POST',
                url: 'menu_ajax.php?action=delete',
                data: menus_record_id,
            }).then(function (response) {
               /*  var index = $scope.users_list.indexOf(user);
                $scope.users_list.splice(index, 1); */
                $scope.success_msg = response.data;
				$scope.menuInformation();
            }, function (error) {
                console.log(error);
            });
        }
    };
	
	$scope.RestoreModal = function (menu) {
        var r = confirm("Are you sure want to Restore ?");
        if (r == true) {
            var menus_record_id = menu.menu_id;
            $http({
                method: 'POST',
                url: 'menu_ajax.php?action=restore',
                data: menus_record_id,
            }).then(function (response) {
                //var index = $scope.users_list.indexOf(user);
                //$scope.users_list.splice(index, 1);
                $scope.success_msg = response.data;
				$scope.menuInformation();
            }, function (error) {
                console.log(error);
            });
        }
    };


});

