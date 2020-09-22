(function () {

    app.controller("ContactsController", function ($scope, ContactsService, LoginService, $timeout) {

        let currentOwner = null;

        $scope.contacts = [];

        function _onInit() {

            // TODO: remove this function and really code the login part (I am just using a default owner in the moment)
            LoginService.loginWithDefaultOwner().then(function () {
                currentOwner = LoginService.getOwner();
                ContactsService.getContactsByOwner(currentOwner.id).then(function (contacts) {
                    $scope.contacts = contacts;
                    prepareContacts();
                    $scope.$apply();
                });
            });
        }

        function prepareContacts() {

            $scope.contacts.forEach(function (contact) {
                contact.addressView = getFormatedAddress(contact.address);
                contact.dateOfBirth = contact.date_of_birth ? new Date(contact.date_of_birth) : "";
                if (contact.photo) {
                    fixContactPhotoDimensions(contact);
                }
            });
        }

        $scope.openAddContactModal = function (contact) {
            $scope.selectedContact = contact || { owner_id: currentOwner.id };
            $scope.addingOrEditingContact = true;
        };

        $scope.closeAddContactModal = function () {
            $scope.addingOrEditingContact = false;
            $scope.selectedContact = null;
            $scope.contactBeingEdited = null;
        }

        $scope.saveContact = function (contact) {
            if (!contact.name) {
                alert("The field Name is required. Please fill it out.");
            } else {
                $scope.savingContact = true;
                const adding = !Boolean(contact.id);
                if (contact.uploadPhotoPromise) {
                    contact.uploadPhotoPromise.finally(function () {
                        delete contact.uploadPhotoPromise;
                        $scope.saveContact(contact)
                    });
                } else {
                    let message;
                    ContactsService.saveContact(contact).then(function (contactObject) {
                        contactObject.addressView = getFormatedAddress(contactObject.address);
                        contactObject.dateOfBirth = contactObject.date_of_birth ? new Date(contactObject.date_of_birth) : "";

                        if (adding) {
                            $scope.contacts.push(contactObject);
                            $scope.selectedContact = { owner_id: currentOwner.id };
                            if (contactObject.photo) {
                                fixContactPhotoDimensions(contactObject);
                            }
                        } else {
                            Object.assign($scope.contactBeingEdited, contact);
                            if ($scope.contactBeingEdited.photo) {
                                fixContactPhotoDimensions($scope.contactBeingEdited);
                            }
                        }

                        message = adding ? "Contact added!" : "Contact saved!";
                        $scope.closeAddContactModal();
                    }).catch(function (error) {
                        console.error(error);
                        message = "Error saving contact: " + error;
                    }).finally(function () {
                        $scope.savingContact = false;
                        $scope.$apply();
                        $timeout(function() {
                            alert(message);
                        }, 10);
                    });
                }
            }
        };

        $scope.editContact = function (contact) {
            if (!contact.deleting) {
                $scope.contactBeingEdited = contact;
                $scope.openAddContactModal(angular.copy(contact));
            }
        };

        $scope.deleteContact = function (contact) {
            contact.deleting = true;
            ContactsService.deleteContact(contact).then(function (response) {
                contact.deleted = true;
                $timeout(function () {
                    removeContact(contact.id);
                    $scope.$apply();
                }, 3000);
            }).catch(function (error) {
                alert("Error deleting contact: " + error);
            }).finally(function () {
                contact.deleting = false;
                $scope.$apply();
            });
        };

        $scope.favoriteContact = function (contact) {
            contact.favorite = !contact.favorite;
            ContactsService.updateFavoriteContact(contact);
        };

        $scope.uploadContactPhoto = function (file) {
            $scope.selectedContact.uploadPhotoPromise = toBase64(file).then(function (data) {
                $scope.selectedContact.photo = data;
            }).catch(function (error) {
                $scope.selectedContact.photo = null;
                console.error(error);
            });
        };

        $scope.getInitials = function (contact) {
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
            return initials;
        };

        function removeContact(contactId) {
            let index = $scope.contacts.findIndex(function (contact) {
                return contact.id === contactId;
            });

            if (index >= 0) {
                $scope.contacts.splice(index, 1);
            }
        }

        function fixContactPhotoDimensions(contact) {
            getContactPhotoDimensions(contact.photo).then(function (dimensions) {
                if (dimensions.height < dimensions.width) {
                    contact.isPhotoLandscape = true;
                } else {
                    contact.isPhotoLandscape = false;
                }
                $scope.$apply();
            });
        }

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

        function toBase64(file) {
            return new Promise(function (resolve, reject) {
                if (file) {
                    const reader = new FileReader();
                    reader.readAsDataURL(file);

                    reader.onload = function () {
                        resolve(reader.result);
                    }

                    reader.onerror = function (error) {
                        reject(error);
                    }
                } else {
                    resolve(null);
                }
            });
        }

        function getFormatedAddress(addressObject) {
            let formatedAddress = "";
            if (addressObject) {
                formatedAddress = addressObject.street;

                if (addressObject.number) {
                    formatedAddress += ", " + addressObject.number;
                }

                if (addressObject.neighborhood) {
                    formatedAddress += ", " + addressObject.neighborhood;
                }
            }
            return formatedAddress;
        }

        _onInit();

    });
})();
