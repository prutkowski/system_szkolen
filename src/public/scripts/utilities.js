/**
 * Globalny dostęp do logiki i danych aplikacji
 */
var Global = {
    /**
     * Dane przekazana z serwera
     * @type Object
     */
    data: {}
};

/**
 * Szablon obiektów backbone'a
 *
 *
 */
var app = {
    models     : {},
    views      : {},
    collections: {}
};

/**
 * Ładowanie template'u z elementu na stronie
 */
template = function(id) {
    return _.template( $('#' + id + "_template").html());
};