import Vue from 'vue'
import notie from 'notie'


if (!String.prototype.includes) {
    String.prototype.includes = function(search, start) {
        'use strict';
        if (typeof start !== 'number') {
            start = 0;
        }

        if (start + search.length > this.length) {
            return false;
        } else {
            return this.indexOf(search, start) !== -1;
        }
    };
}

window.addEventListener("load", function(event) {
    require('./manager')
    Vue.component('EasyMediaModal', require('./components/easy-media-modal').default)
    Vue.component('EasyMediaDisplay', require('./components/easy-media-display').default)

    new Vue({
        el: document.querySelector([".field-easy-media .form-widget","#media-holder"])
    })

    window.EventHub.listen("showNotif", (obj) => {
        notie.alert({ type: obj.type, text: '<small>' + obj.body + '</small>', time: obj.duration })
    })
});

