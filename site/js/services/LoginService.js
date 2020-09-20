(function () {

    app.service("LoginService", function ($http) {

        let LoginService = this;
        let currentUser = null;

        function _onInit() {
            LoginService.doLogin = _doLogin;
            LoginService.getUser = _getUser;
            LoginService.loginWithDefaultUser = _loginWithDefaultUser;
        }

        function _doLogin(email, password) {
            return $http.post(API_URL + "login", { email: email, password: password }).then(function (response) {
                console.log(response);
                currentUser = response.data.data.owner;
            }).catch(function (error) {
                console.error(error);
            });
        }

        function _getUser() {
            return currentUser;
        }

        /* Used while the login part is not implemented */
        function _loginWithDefaultUser() {
            let defaultUser = {
                email: "admin@test.com",
                password: "123"
            };

            return LoginService.doLogin(defaultUser.email, defaultUser.password);
        }

        _onInit();
    });
})();
