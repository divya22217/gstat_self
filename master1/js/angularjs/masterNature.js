var myapp = angular.module('my_app',['datatables']);
myapp.controller('users', function($scope,$http){
	$scope.master = {};
	$scope.usersInformation = function () {
		$http({ 
			method: 'GET',
			url: 'master_nature_order.php'
		})
		.then(function (success){
			$scope.users_list = [];
			$scope.users_list =success.data;	
		},function (error){
				console.log(error);
		});
	};
	


    $scope.addModal = function() {
		$scope.users_form = angular.copy($scope.master);
        $scope.form_name = 'Add Nature Of Order';
        $('#form_modal').modal('show');
    };
    $scope.UserAddUpdate = function (users_form) {
    	
       var users_information = users_form;
       $http({
         method: 'POST',
         url: 'NatuerAddUpdate.php',
         data: users_information,
        }).then(function(response) {
        	
            $scope.usersInformation();
			$scope.success_msg = response.data;
        },function (error){
			console.log(error);
		});
       $('#form_modal').modal('hide');
    };
    $scope.EditModal = function(user) {
    	
        $scope.form_name = 'Edit Nature Of Order';
		var edit_form = {};
		angular.copy(user, edit_form);
		$scope.users_form = edit_form;
		
		$scope.users_form.dob = new Date($scope.users_form.dob);		
        $('#form_modal').modal('show');
    };
	$scope.DeleteModal = function(user) {
		var r = confirm("Are you sure want to delete ?");
		if (r == true) {
			var users_record_id = user.nature_code;
			
			$http({
				method: 'POST',
				url: 'NatureDelete.php',
				data: users_record_id,
			}).then(function(response) {
				
				var index = $scope.users_list.indexOf(user);
				$scope.users_list.splice(index, 1);	
				$scope.success_msg = response.data;
			},function (error){
				console.log(error);
			});
		}
    };
});