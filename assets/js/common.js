var common = angular.module('myApp', []);
common.directive('wbHeaderindex', function () {
    return {
        restrict: 'E'
        , controller: function ($scope, $rootScope) {}
        , templateUrl: 'header_index.html'
    };
});
common.directive('wbHeader', function () {
    return {
        restrict: 'E'
        , controller: function ($scope, $rootScope) {}
        , templateUrl: 'header.html'
    };
});
common.directive('wbHeadermem', function () {
    return {
        restrict: 'E'
        , controller: function ($scope, $rootScope) {}
        , templateUrl: 'headerMem.html'
    };
});
common.directive('wbNavbar', function () {
    return {
        restrict: 'E'
        , controller: function ($scope, $rootScope) {}
        , templateUrl: 'navbar.html'
    };
});
common.directive('wbModal', function () {
    return {
        restrict: 'E'
        , controller: function ($scope, $rootScope) {}
        , templateUrl: 'modal.html'
    };
});
common.directive('wbFooterindex', function () {
    return {
        restrict: 'E'
        , controller: function ($scope, $rootScope) {}
        , templateUrl: 'footer_index.html'
    };
});
common.directive('wbFooter', function () {
    return {
        restrict: 'E'
        , controller: function ($scope, $rootScope) {}
        , templateUrl: 'footer.html'
    };
});
common.directive('wbPopup', function () {
    return {
        restrict: 'E'
        , controller: function ($scope, $rootScope) {}
        , templateUrl: 'popup.html'
    };
});

// common.run(function () {
//     $(".body").css("display", "none");
//     setTimeout(function () {
//         $(".body").css({
//             "display": "block"
//             ,"animation-name": "fadeIn"
//             , "animation-duration": "0.5s"
//         });
//     }, 500);
// });
common.controller('mainController', function($scope,$http) {
    $scope.u={
        name:null,
        email:null,
        mobile:null,
        designation:null,
        company:null,       
    }
     $scope.isSent=false;
     $scope.isError=false;
	$scope.submitForm = function(isValid) {		
		if (isValid) { 	
            $http.post('email.php',$scope.u).then(function(data){
                
                if(data.data=="success"){
                   $scope.isSent=true;
                }else{
                     $scope.isError=true;
                }
            }).catch(function(data){
                
                
            })
		}
	};
});































//common.run(function () {
//    $("body").css("display", "none");
//    setTimeout(function () {
//        $("body").css({
//            "display": "block"
//            ,"animation-name": "fadeIn"
//            , "animation-duration": "1s"
//        });
//    }, 500);
//});