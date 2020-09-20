(function () {

    app.service("ContactsService", function ($http) {

        let ContactsService = this;

        function _onInit() {
            ContactsService.getContactsByOwner = _getContactsByOwner;
        }

        function _getContactsByOwner(ownerId) {
            return new Promise(function (resolve, reject) {

                let url = API_URL + "owner/" + ownerId + "/contacts";

                $http.get(url).then(function (response) {
                    resolve(response.data.data);
                }).catch(function (error) {
                    console.error(error);
                    reject(error);
                });
            });
        }

        _onInit();

    });
})();
