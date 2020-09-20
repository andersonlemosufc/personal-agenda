(function () {

    app.controller("ContactsController", function ($scope, ContactsService, LoginService) {

        function _onInit() {

            // TODO: remove this function and really code the login part (I am just using a default user in the moment)
            LoginService.loginWithDefaultUser().then(function () {
                console.log(LoginService.getUser());
            });
        }

        _onInit();

    });
})();
