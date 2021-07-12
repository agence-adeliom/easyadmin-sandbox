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
        const types = {
            "danger": "error",
            "info": "info",
            "success": "success",
            "warning": "warning",
            "link": "neutral",
        }
        let type = types[obj.type] ? types[obj.type] : types.link;
        let duration = obj.duration ? obj.duration : 5;
        notie.alert({ type: type, text: '<small>' + obj.body + '</small>', time: duration })
    })
});

