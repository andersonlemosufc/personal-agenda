(function () {

    app.controller("ContactsController", function ($scope, ContactsService, LoginService) {

        let currentOwner = null;

        $scope.contacts = [];

        function _onInit() {

            // TODO: remove this function and really code the login part (I am just using a default owner in the moment)
            LoginService.loginWithDefaultOwner().then(function () {
                currentOwner = LoginService.getOwner();
                ContactsService.getContactsByOwner(1).then(function (contacts) {
                    $scope.contacts = contacts;
                    prepareContacts();
                });
            });
        }

        function prepareContacts() {

            let promises = [];
            $scope.contacts.forEach(function (contact) {
                contact.viewAddress = contact.address ? contact.address.street + ", " + contact.address.number + ", " + contact.address.neighborhood : "";
                contact.date_of_birth = contact.date_of_birth ? new Date(contact.date_of_birth) : "";
                if (contact.photo) {
                    let promise = getContactPhotoDimensions(contact.photo).then(function (dimensions) {
                        if (dimensions.height < dimensions.width) {
                            contact.isPhotoLandscape = true;
                        }
                    });
                }
            });

            Promise.all(promises).then(function () {
                $scope.$apply();
            });
        }

        $scope.openAddContactModal = function () {

        };

        $scope.editContact = function (contact) {
            if (!contact.deletingContact) {
            }
        };

        $scope.deleteContact = function (contact) {
        };

        $scope.favoriteContact = function (contact) {
            contact.favorite = !contact.favorite;
        };

        $scope.getInitials = function (contact) {
            console.log("getInitials");
            let initials = "";
            if (contact.name && contact.name.length) {
                let splitedName = contact.name.split(" ");
                if (splitedName.length > 1 && splitedName[0].length && splitedName[1].length) {
                    initials = splitedName[0][0].toUpperCase() + splitedName[1][0].toUpperCase();
                } else {
                    initials = contact.name[0].toUpperCase();
                    if (contact.name.length > 1) {
                        initials += contact.name[1].toLowerCase();
                    }
                }
            }
            console.log(initials);
            return initials;
        };

        function getContactPhotoDimensions(photo) {
            return new Promise (function (resolve, reject) {
                let image = new Image();

                image.onload = function () {
                    let dimensions = {
                        width: image.width,
                        height: image.height
                    };
                    resolve(dimensions);
                };

                image.src = photo;
            });
        }

        _onInit();

    });
})();
