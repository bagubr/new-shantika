var app = new Vue({
    el: '#app',
    data: {
        message: 'Hello Vue!',
        test: [],
        area_id: 1,
        agency: [{
            id: '',
        }]
    },
    mounted() {
        this.getData();
    },
    methods: {
        getData() {
            axios
                .get('/agency/all', {
                    params: {
                        area_id: this.area_id
                    },
                })
                .then(result => {
                    this.test = result.data.agencies;
                    this.agency = [
                        {
                            id: ''
                        }
                    ];
                });
        },
        addField() {
            this.agency.push({ id: '' })
        },
        removeField(index) {
            this.agency.splice(index, 1);
        },
    },
})