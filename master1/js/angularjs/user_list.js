var myapp = angular.module('my_app', ['datatables']);
myapp.controller('users', function ($scope, $http) {
    $scope.master = {};
    $scope.usersInformation = function () {
        $http({
            method: 'GET',
            url: 'user_ajax.php?action=organization_list'
        })
            .then(function (success) {
                $scope.users_list = [];
                $scope.users_list = success.data;
            }, function (error) {
                console.log(error);
            });
    };var myapp = angular.module('my_app', ['datatables']);
myapp.controller('users', function ($scope, $http) {
    $scope.master = {};
    $scope.usersInformation = function () {
        $http({
            method: 'GET',
            url: 'user_ajax.php?action=organization_list'
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
        $scope.form_name = 'Add Users';
        $("#users_form_id #action_text").val('insert');
        $('#form_modal').modal('show');
    };


    
    $scope.EditPasswordModal = function (user) {
        $scope.form_name = 'Edit User Password';
        var edit_form = {};
        angular.copy(user, edit_form);
        $scope.users_form = edit_form;
        $('#form_Password_modal').modal('show');
    };
    
    $scope.ChangepasswordModal = function (users_form) {
        var users_information = users_form;
        var action_value = $("#users_form_id #action_password_text").val();
        $http({
            method: 'POST',
            url: 'user_ajax.php?action=' + action_value,
            data: users_information,
        }).then(function (response) {
            $scope.usersInformation();
            $scope.success_msg = response.data;
        }, function (error) {
            console.log(error);
        });
        $('#form_Password_modal').modal('hide');
    };
    
    
    
    $scope.menu_access = function (user_id) {
        $("#menu_users_form_id #user_id").val(user_id);
        $http({
            method: 'POST',
            url: 'user_ajax.php?action=menu_access&user_id='+user_id,
            data: '',
        }).then(function (response) {
            //console.log(response.data);
            $("#menu_access_id").html(response.data);
           // $scope.success_msg = response.data;
        }, function (error) {
            console.log(error);
        });
        $('#menu_form_modal').modal('show');
    };

    $scope.EditModal = function (user) {
        $scope.form_name = 'Edit Users';
        var edit_form = {};
        angular.copy(user, edit_form);
        $scope.users_form = edit_form;
        $('#form_modal').modal('show');
    };

    $scope.UserAddUpdate = function (users_form) {
        var users_information = users_form;
        var action_value = $("#users_form_id #action_text").val();
        $http({
            method: 'POST',
            url: 'user_ajax.php?action=' + action_value,
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
            var users_record_id = user.id;
            $http({
                method: 'POST',
                url: 'user_ajax.php?action=delete',
                data: users_record_id,
            }).then(function (response) {
               /*  var index = $scope.users_list.indexOf(user);
                $scope.users_list.splice(index, 1); */
                $scope.success_msg = response.data;
				$scope.usersInformation();
            }, function (error) {
                console.log(error);
            });
        }
    };
	
	$scope.RestoreModal = function (user) {
        var r = confirm("Are you sure want to Restore ?");
        if (r == true) {
            var users_record_id = user.id;
            $http({
                method: 'POST',
                url: 'user_ajax.php?action=restore',
                data: users_record_id,
            }).then(function (response) {
                //var index = $scope.users_list.indexOf(user);
                //$scope.users_list.splice(index, 1);
                $scope.success_msg = response.data;
				$scope.usersInformation();
            }, function (error) {
                console.log(error);
            });
        }
    };


});


    $scope.addModal = function () {
        $scope.users_form = angular.copy($scope.master);
        $scope.form_name = 'Add Users';
        $("#users_form_id #action_text").val('insert');
        $('#form_modal').modal('show');
    };


    
    $scope.EditPasswordModal = function (user) {
        $scope.form_name = 'Edit User Password';
        var edit_form = {};
        angular.copy(user, edit_form);
        $scope.users_form = edit_form;
        $('#form_Password_modal').modal('show');
    };
    
    $scope.ChangepasswordModal = function (users_form) {
        var users_information = users_form;
        var action_value = $("#users_form_id #action_password_text").val();
        $http({
            method: 'POST',
            url: 'user_ajax.php?action=' + action_value,
            data: users_information,
        }).then(function (response) {
            $scope.usersInformation();
            $scope.success_msg = response.data;
        }, function (error) {
            console.log(error);
        });
        $('#form_Password_modal').modal('hide');
    };
    
    
    
    $scope.menu_access = function (user_id) {
        $("#menu_users_form_id #user_id").val(user_id);
        $http({
            method: 'POST',
            url: 'user_ajax.php?action=menu_access&user_id='+user_id,
            data: '',
        }).then(function (response) {
            //console.log(response.data);
            $("#menu_access_id").html(response.data);
           // $scope.success_msg = response.data;
        }, function (error) {
            console.log(error);
        });
        $('#menu_form_modal').modal('show');
    };

    $scope.EditModal = function (user) {
        $scope.form_name = 'Edit Users';
        var edit_form = {};
        angular.copy(user, edit_form);
        $scope.users_form = edit_form;
        $('#form_modal').modal('show');
    };

    $scope.UserAddUpdate = function (users_form) {
        var users_information = users_form;
        var action_value = $("#users_form_id #action_text").val();
        $http({
            method: 'POST',
            url: 'user_ajax.php?action=' + action_value,
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
            var users_record_id = user.id;
            $http({
                method: 'POST',
                url: 'user_ajax.php?action=delete',
                data: users_record_id,
            }).then(function (response) {
               /*  var index = $scope.users_list.indexOf(user);
                $scope.users_list.splice(index, 1); */
                $scope.success_msg = response.data;
				$scope.usersInformation();
            }, function (error) {
                console.log(error);
            });
        }
    };
	
	$scope.RestoreModal = function (user) {
        var r = confirm("Are you sure want to Restore ?");
        if (r == true) {
            var users_record_id = user.id;
            $http({
                method: 'POST',
                url: 'user_ajax.php?action=restore',
                data: users_record_id,
            }).then(function (response) {
                //var index = $scope.users_list.indexOf(user);
                //$scope.users_list.splice(index, 1);
                $scope.success_msg = response.data;
				$scope.usersInformation();
            }, function (error) {
                console.log(error);
            });
        }
    };


});

