(function () {
    var app;

    app = angular.module('phalcon_test.create_person', ['ngResource']);

    app.factory('CallPerson', [
        '$resource', function ($resource) {
            return $resource('/phalcon_test_api/person/create');
        }
    ]);

    app.config(['$httpProvider', function ($httpProvider) {
        $httpProvider.defaults.headers.common['X-CSRFToken'] = '{{ csrf_token|escapejs }}';
    }]);

    app.controller('CreateController', [
        '$scope', 'CallPerson', function ($scope, CallPerson) {
            $scope.newPerson = new CallPerson();
            return $scope.save = function () {
                $scope.errors = [];
                var tmp = $scope.newPerson;
                if (tmp.firstname == undefined || tmp.lastname == undefined) {
                    $scope.errors=["Firstname and Lastname cannot be empty"]
                    return;
                }
                $scope.newPerson = new CallPerson();
                return CallPerson.save(tmp, function (res) { });
            };
        }
    ]);

}).call(this);