(function () {

    app.service("LoginService", function ($http) {

        let LoginService = this;
        let currentOwner = null;

        function _onInit() {
            LoginService.doLogin = _doLogin;
            LoginService.getOwner = _getOwner;
            LoginService.loginWithDefaultOwner = _loginWithDefaultOwner;
        }

        function _doLogin(email, password) {
            return $http.post(API_URL + "login", { email: email, password: password }).then(function (response) {
                currentOwner = response.data.data.owner;
            }).catch(function (error) {
                console.error(error);
            });
        }

        function _getOwner() {
            return currentOwner;
        }

        /* Used while the login part is not implemented */
        function _loginWithDefaultOwner() {
            let defaultOwner = {
                email: "admin@test.com",
                password: "123"
            };

            return LoginService.doLogin(defaultOwner.email, defaultOwner.password);
        }

        _onInit();
    });
})();
