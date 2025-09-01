/**
 * @package storefront
 */
export default class CookieStorageHelper {

    /**
     * returns if cookies are supported
     *
     * @returns {boolean}
     */
    static isSupported() {
        return document.cookie !== 'undefined';
    }

    /**
     * Sets cookie with name, value and expiration date
     *
     * @param {string} key
     * @param {string} value
     */
    static setItem(key, value) {
        if (typeof key === 'undefined' || key === null) {
            throw new Error('You must specify a key to set a cookie');
        }
        localStorage.setItem(key, value);
    }

    /**
     * Gets cookie value through the cookie name
     *
     * @param {string} key
     *
     * @returns {string} cookieValue
     */
    static getItem(key) {
        return localStorage.getItem(key);
    }

    /**
     * removes a cookie
     *
     * @param key
     */
    static removeItem(key) {
        localStorage.removeItem(key);
    }

    /**
     * cookies don't support this options
     *
     * @returns {string}
     */
    static key() {
        return '';
    }

    /**
     * cookies don't support this options
     *
     * @returns {string}
     */
    static clear() {
    }
}
