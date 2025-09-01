import Plugin from '@friendsofshopware/storefront-sdk/plugin-system/plugin.class';
import CookieStorage from '../helper/cookie-storage.helper'

export default class PopupPlugin extends Plugin {
    constructor(el, options, pluginName) {
        super(el, options, pluginName);

        this.setup();
    }

    /**
     *
     */
    init() {
        this.delayTime = this.el.dataset.delayShowTime
        this.popupId = this.el.dataset.popupId
        this.expirationDays = this.el.dataset.cookieExpirationDays
        this.closeButton = this.el.querySelector('.alpha-popup--close')
        this.scrollActivePercentage = this.el.dataset.scrollActivation
        this.jsEvents = this.el.dataset.jsEvents.replaceAll(" ", "").split(',');
        this.elementSelectors = this.el.dataset.elementSelectors.replaceAll(" ", "").split(',');

        const dontShowPopup = CookieStorage.getItem(`dontShowPopup-${this.popupId}`);

        if (dontShowPopup) {
            const expirationDate = CookieStorage.getItem(`dontShowExpiration-${this.popupId}`);
            if (expirationDate === "indefinite" || expirationDate > Date.now()) {
                return
            } else {
                CookieStorage.removeItem(`dontShowPopup-${this.popupId}`);
                CookieStorage.removeItem(`dontShowExpiration-${this.popupId}`);
            }
        }

        if (this.scrollActivePercentage === "0") {
            setTimeout(() => {
                this.show()
            }, this.delayTime);
        } else if (this.scrollActivePercentage !== "999") {
            const fullHeight = document.documentElement.scrollHeight;

            const detectScroll = () => {
                const scrolled = window.scrollY;
                let scrollPercentage = (scrolled / (fullHeight - window.innerHeight)) * 100;
                if (scrollPercentage >= this.scrollActivePercentage) {

                    setTimeout(() => {
                        this.show()
                    }, this.delayTime);
                    window.removeEventListener('scroll', detectScroll);
                }
            };
            window.addEventListener('scroll', detectScroll);
        }

        this.jsEvents.forEach((event) => {
            if (event.length > 0) {
                document.addEventListener(event, () => {
                    this.showParent(this)
                })
                window.addEventListener(event, () => {
                    this.showParent(this)
                })
            }
        })

        this.elementSelectors.forEach((selector) => {
            if (selector.length > 0) {
                const element = document.querySelector(selector);
                console.log(element)
                if (element) {
                    element.addEventListener('click', () => {
                        this.showParent(this)
                    });
                }
            }
        })

        this.registerEvents();
    }

    /**
     *
     */
    show() {
        this.el.style.display = 'block';
    }

    showParent(plugin) {
        plugin.el.style.display = "block";
    }

    /**
     *
     */
    hide() {
        this.el.style.display = 'none';
    }

    /**
     *
     */
    registerEvents() {
        this.closeButton.addEventListener('click', this.onClosePopup.bind(this))
    }

    /**
     *
     */
    onClosePopup(event) {
        event.preventDefault();
        this.hide();

        CookieStorage.setItem(`dontShowPopup-${this.popupId}`, 'true');
        if (this.expirationDays === "999") {
            CookieStorage.setItem(`dontShowExpiration-${this.popupId}`, "indefinite")
        } else {
            const date = new Date();
            date.setTime(date.getTime() + (this.expirationDays * 24 * 60 * 60 * 1000));
            CookieStorage.setItem(`dontShowExpiration-${this.popupId}`, date.getTime().toString())
        }
    }
}
