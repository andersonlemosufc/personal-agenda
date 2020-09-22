(function () {

    app.service("ContactsService", function ($http, $timeout) {

        let ContactsService = this;

        function _onInit() {
            ContactsService.saveContact = _saveContact;
            ContactsService.updateFavoriteContact = _updateFavoriteContact;
            ContactsService.deleteContact = _deleteContact;
            ContactsService.getContactsByOwner = _getContactsByOwner;
        }

        function _saveContact(contact) {
            return new Promise(function (resolve, reject) {
                let adding = !contact.id;
                let url = API_URL + "contact";
                let data = {
                    "id": contact.id,
                    "name": contact.name,
                    "phone": contact.phone || "",
                    "email": contact.email || "",
                    "date_of_birth": contact.dateOfBirth || "",
                    "comments": contact.comments || "",
                    "photo": contact.photo,
                    "owner_id": contact.owner_id
                };

                let requestPromise;
                if (adding) {
                    data["address"] = { "street": contact.addressView || "", "number": "", "neighborhood": "" };;
                    requestPromise = $http.post(url, data);
                } else {
                    requestPromise = updateContactAddress(contact).then(function () {
                        return $http.put(url, data);
                    }, reject);
                }

                requestPromise.then(function (response) {
                    if (response.data.success == true) {
                        resolve(response.data.data.object);
                    } else {
                        reject(response);
                    }
                }).catch(function (error) {
                    console.error(error);
                    reject(error);
                });

            });
        }

        function _updateFavoriteContact(contact) {
            return new Promise(function (resolve, reject) {
                let url = API_URL + "contact";
                let data = {
                    "id": contact.id,
                    "favorite": Boolean(contact.favorite)
                }
                $http.put(url, data).then(function (response) {
                    if (response.data.success == true) {
                        resolve(response.data.data.object);
                    } else {
                        reject(response);
                    }
                }).catch(function (error) {
                    console.error(error);
                    reject(error);
                });
            });
        }

        function _deleteContact(contact) {
            return new Promise(function (resolve, reject) {
                let url = API_URL + "contact";
                let config = { "data": { "id": contact.id } };
                $http.delete(url, config).then(function (response) {
                    if (response.data.success == true) {
                        if (contact.address && contact.address.id) {
                            deleteContactAddress(contact).then(resolve, reject);
                        } else {
                            resolve(response);
                        }
                    } else {
                        reject(response);
                    }
                }).catch(function (error) {
                    console.error(error);
                    reject(error);
                });
            });
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

        function updateContactAddress(contact) {
            return new Promise(function (resolve, reject) {
                let url = API_URL + "address";
                let data = {
                    "id": contact.address.id,
                    "street": contact.addressView || "",
                    "number": "",
                    "neighborhood": ""
                };

                $http.put(url, data).then(function (response) {
                    if (response.data.success == true) {
                        resolve(response.data.data.object);
                    } else {
                        reject(response);
                    }
                }).catch(function (error) {
                    console.error(error);
                    reject(error);
                });
            });
        }

        function deleteContactAddress(contact) {
            return new Promise(function (resolve, reject) {
                let url = API_URL + "address";
                let config = { "data": { "id": contact.address.id } };
                $http.delete(url, config).then(function (response) {
                    if (response.data.success == true) {
                        resolve(response);
                    } else {
                        reject(response);
                    }
                }).catch(function (error) {
                    console.error(error);
                    reject(error);
                });
            });
        }

        _onInit();

    });
})();
