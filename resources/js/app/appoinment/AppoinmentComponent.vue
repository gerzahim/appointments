<template>

    <div>
        <form>
            <input type="hidden" name="_token" :value="csrf">
            <!-- Date Form -->
            <div class="form-group-po">
                <label class="mb-0" ><small>Seleccione una Fecha: <span style="color: red">*</span></small></label>
                <b-form-datepicker id="example-datepicker" v-bind:class="[errors.date ? 'is-invalid' : '']" v-model="form_date" size="sm" class="form-control form-control-sm mb-2"></b-form-datepicker>
                <input type="hidden" name="date" id="date" :value="form_date">
                <div v-show="errors.date" class="invalid-feedback">Seleccione la Fecha</div>
            </div>
            <!-- Time Form -->
            <div v-show="!loading_times">
                <label class="mb-0"><small>Seleccione una Hora: <span style="color: red">*</span></small></label>
                <select id="client_id" name="client_id" class="form-control form-control-sm" v-bind:class="[ errors.time ? 'is-invalid' : '']" v-model="form_time">
                    <option value="0" disabled selected>Seleccione la Hora</option>
                    <option v-for="slot in slot_times" :value="slot.time" :key="slot.id">
                        {{ slot.name }}
                    </option>
                </select>
                <div v-show="errors.time" class="invalid-feedback">Please Select a Time!</div>
            </div>
            <!-- Client Form -->
            <div class="form-group-po">
                <label class="mb-0" ><small>Nombre: <span style="color: red">*</span></small></label>
                <input type="text" class="form-control form-control-sm" id="name" name="name" v-bind:class="[ errors.name ? 'is-invalid' : '']" v-model="form_name" v-bind:readonly="isReadOnly">
                <div v-show="errors.name" class="invalid-feedback">Por Favor escriba el Nombre!</div>

                <label class="mb-0" ><small>Telefono: <span style="color: red">*</span></small></label>
                <input type="text" class="form-control form-control-sm" id="phone" name="phone" v-bind:class="[ errors.phone ? 'is-invalid' : '']" v-mask="'+1 (###) ###-####'" v-model="form_phone" v-bind:readonly="isReadOnly">
                <div v-show="errors.phone" class="invalid-feedback">Por Favor escriba el Telefono!</div>
                <div v-show="errors.phone_invalid" class="invalid-feedback">Telefono Invalido!</div>

                <label class="mb-0" ><small>Email: <span style="color: red">*</span></small></label>
                <input type="text" class="form-control form-control-sm" id="email" name="email" v-bind:class="[ errors.email ? 'is-invalid' : '']" v-model="form_email" v-bind:readonly="isReadOnly">
                <div v-show="errors.email" class="invalid-feedback">Por Favor escriba el Email!</div>
                <div v-show="errors.email_invalid" class="invalid-feedback">Email Invalido!</div>

                <label class="mb-0" ><small>Nota:</small></label>
                <textarea class="form-control" id="notes" name="notes" rows="2" v-model="form_note"></textarea>

                <div v-show="loading_services" class="spinner-border text-success" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <div v-show="!loading_services">
                    <label class="mb-0"><small>Servicios: <span style="color: red">*</span></small></label>
                        <select id="service_id" name="service_id"
                            class="form-control form-control-sm"
                            v-bind:class="[ errors.service ? 'is-invalid' : '']"
                            v-model="form_service_id"
                        >
                        <option value="0" disabled selected=selected>Seleccione un Servicio</option>
                        <option v-for="service in services" :value="service.id" :key="service.id">
                            {{ service.name }}
                        </option>
                    </select>
                    <div v-show="errors.service" class="invalid-feedback">
                        Por favor seleccione un servicio!
                    </div>
                </div>

            </div>
            <!-- Buttons Form -->
            <div class="form-actions mt-2 d-flex flex-row-reverse d">

                    <!-- 
                    <button type="button" class="btn btn-primary"
                        @click="createAppoinment()"  v-show="!creating_appointment">
                        <i class="icon ion-md-calendar"></i>
                        Agendar Cita
                    </button>

                    <vue-recaptcha
                        ref="recaptcha"
                        @verify="onVerify"
                        @expired="onExpired"
                        :sitekey="sitekey">
                    </vue-recaptcha>
                    <button @click="resetRecaptcha"> Reset ReCAPTCHA </button>
                    

                    <vue-recaptcha
                        ref="invisibleRecaptcha"
                        @verify="onVerify"
                        @expired="onExpired"
                        size="invisible"
                        :sitekey="sitekey">
                    </vue-recaptcha>
                    -->
                    <vue-recaptcha
                        ref="invisibleRecaptcha"
                        @verify="onVerify"
                        @expired="onExpired"
                        size="invisible"
                        :sitekey="sitekey">
                        <button type="button" class="btn btn-primary"
                            @click="createAppoinment()"  v-show="!creating_appointment">
                            <i class="icon ion-md-calendar"></i>
                            Agendar Cita
                        </button>
                    </vue-recaptcha>
                    &nbsp;
                    <button class="btn btn-secondary"
                        @click="slot_times = []">
                        <i class="icon ion-md-search"></i>
                        Reiniciar
                    </button>
                    <div class="spinner-border text-success align-middle" role="status" v-show="creating_appointment">
                        <span class="sr-only">Loading...</span>
                    </div>
            </div>
        </form>
    </div>

</template>

<script>
    import VueRecaptcha from 'vue-recaptcha';
    export default {
        components: {
            'vue-recaptcha': VueRecaptcha
        },
        props: ["props_only_component"],
        data: function () {
            return {
                csrf: document.head.querySelector('meta[name="csrf-token"]').content,

                sitekey: '6LcPU0caAAAAAKH7qFTKEdzk2RZji6nkU2k1uHo_',

                loading_times          :false,
                loading_services       :false,
                creating_appointment   :false,
                isReadOnly             :false,

                // var form
                form_date       : '',
                form_time       : '',
                form_time_name  : '',
                form_name       : '',
                form_email      : '',
                form_phone      : '',
                form_note       : '',
                form_service_id : '',

                // var errors
                errors: {
                    date   : false,
                    time   : false,
                    name   : false,
                    email  : false,
                    email_invalid : false,
                    phone  : false,
                    phone_invalid : false,
                    service: false,
                },

                slot_times     : [],
                showTimes      : false,
                selectedTime   : {},
                services       : [],
                parameters     : {},
                analysts_count : 0,

            }
        },
        computed: {
            computedAction: function() {
                if(this.action_edit){
                    return `/order/${this.form_id}`
                }
                // form action_create
                return `/order`
            },
            axiosParams() {
                const params = new URLSearchParams();
                params.append('date', this.form_date);
                return params;
            }
        },
        watch: {
            form_date() {
                if(this.isDateSelectedValid()){
                    this.loadTimes();
                }
            },

            form_email(){
                this.validateEmail();
            },
            form_phone(){
                this.validatePhone();
            },
            selectedTime() {
                this.form_time = this.selectedTime.time;
                this.form_time_name = this.selectedTime.name;
            }
        },
        methods: {
            onSubmit: function () {
                this.$refs.invisibleRecaptcha.execute()
            },
            onVerify: function (response) {
                console.log('Verify: ' + response)
            },
            onExpired: function () {
                console.log('Expired')
            },
            resetRecaptcha () {
                this.$refs.recaptcha.reset() // Direct call reset method
            },
            loadTimes: function () {
                this.loading_times = true
                this.form_time = ''
                axios.get('/api/get-time-available/'+this.form_date+'/'+this.analysts_count)
                    .then((response) => {
                        this.slot_times    = response.data;
                        this.loading_times = false
                        this.showTimes     = true
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            loadServices: function () {
                this.loading_services = true
                axios.get('/api/get-services/')
                    .then((response) => {
                        this.services         = response.data.data;
                        this.loading_services = false
                    });
            },
            getAnalystCount: function () {
                axios.get('/api/get-analysts-active/')
                    .then((response) => {
                        this.analysts_count = response.data;
                    });
            },
            validateEmail(){

                if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.form_email))
                {
                    this.errors.email_invalid = false
                } else{
                    this.errors.email_invalid = true
                }
            },
            validatePhone() {
                /*
                ^\+1 ([0-9]{3}) [0-9]{3}-[0-9]{4}$
                if (!phone.length) {
                    return { valid: false, error: 'This field is required.' };
                }

                if (!phone.match(/^[+][(]?[0-9]{1,3}[)]?[-\s.]?[0-9]{3}[-\s.]?[0-9]{4,7}$/gm)) {
                    return { valid: false, error: 'Please, enter a valid international phone number.' };
                }
                return { valid: true, error: null };
                */
                if (/^[\d -\\(\\)]{17,18}$/.test(this.form_phone)) {
                    this.errors.phone_invalid = false
                }else{
                    this.errors.phone_invalid = true
                }
            },
            isDateSelectedValid() {
                this.errors.date = false

                // Getting today date based on Easter time without hours format 'YYYY-mm-dd'
                var myDate = new Date(this.form_date);
                var localtime = new Date().toLocaleDateString();
                var d = new Date(localtime);
                var onlydate = d.getFullYear() + "-" + ("0"+(d.getMonth()+1)).slice(-2) + "-" + ("0" + d.getDate()).slice(-2)
                var today = new Date(onlydate);

                // is date selected a weeked day
                if(myDate.getDay() == 6 || myDate.getDay() == 5) {
                    this.errors.date = true;
                    toastr.error('Invalid Date: select only Weekdays', 'Error Alert', {timeOut: 5000})
                    return false;
                }
                // is date selected before today
                if (myDate < today){
                    this.errors.date = true;
                    toastr.error('Invalid Date: select today or some day forward', 'Error Alert', {timeOut: 5000})
                    return false;
                }

                return true;

            },
            areFieldsFormValid() {
                this.errors.date = false
                if (this.form_date === '') {
                    this.errors.date = true
                }
                this.errors.time = false
                if (this.form_time === '') {
                    this.errors.time = true
                }
                this.errors.name = false
                if (this.form_name === '') {
                    this.errors.name = true
                }

                this.errors.email = false
                if (this.form_email === '') {
                    this.errors.email = true
                }

                this.errors.phone = false
                if (this.form_phone === '') {
                    this.errors.phone = true
                }

                this.errors.service = false
                if (this.form_service_id === '') {
                    this.errors.service = true
                }

                if ( this.errors.date || this.errors.time || this.errors.name || this.errors.email || this.errors.phone || this.errors.service)  {
                    toastr.error('Empty some Input Fields!', 'Error Alert', {timeOut: 5000})
                    console.log('Empty some Input Fields!');
                    return false;
                }

                if (this.errors.email_invalid)  {
                    toastr.error('Invalid Email Address!', 'Error Alert', {timeOut: 5000})
                    console.log('Invalid Email Address!');
                    return false;
                }

                if (this.errors.phone_invalid)  {
                    toastr.error('Invalid Phone Number! Valid Format +1 (###) ###-####', 'Error Alert', {timeOut: 5000})
                    console.log('Invalid Phone Number!');
                    return false;
                }

                return true;



            },

            createAppoinment() {
                
                if( this.areFieldsFormValid() ){
                    
                    //this.onSubmit()
                    //this.creating_appointment = true
                    console.log('Creating appoinment...');
                    axios.post('/api/set-appoinment', {
                            date       : this.form_date,
                            time       : this.form_time,
                            name       : this.form_name,
                            email      : this.form_email,
                            phone      : this.form_phone,
                            note       : this.form_note,
                            service_id : this.form_service_id,
                        })
                        .then(function(response) {
                            console.log('New Appoinment ID: ' + response.data.data.id);
                            // Display a success toast, with a title
                            toastr.success('Your appointment has been confirmed!', 'Congratulations', {timeOut: 5000})
                        })
                        .catch(function(error) {
                            
                            let error_message = 'Fail Creating appointment some information are not right'

                            if (typeof(error.response.data.message) != 'undefined') {
                                console.log(' error.response.data.message is defined');
                                if(error.response.data.message){
                                    error_message = error.response.data.message
                                }
                            }
                            //this.creating_appointment = false
                        
                            toastr.error(error_message, 'Error Alert', {timeOut: 5000});
                        }
                    );                    
                }
            },
            getParamsFromUrl(){
                let uri = window.location.href.split('?');
                if (uri.length == 2) {
                    let vars = uri[1].split('&');
                    let getVars = {};
                    let tmp = '';
                    vars.forEach(function(v){
                        tmp = v.split('=');
                        if(tmp.length == 2)
                        getVars[tmp[0]] = tmp[1];
                    });
                    this.parameters = getVars;

                    //this.form_name = this.parameters.name;
                    if (typeof(this.parameters.name) != 'undefined') {
                        this.form_name = this.parameters.name;
                    }

                    //this.form_phone = this.parameters.phone;
                    if (typeof(this.parameters.phone) != 'undefined') {
                        //this.form_phone = this.parameters.phone;
                        this.form_phone = this.FormatPhonenNumber(this.parameters.phone);
                    }

                    //this.form_email = this.parameters.email;
                    if (typeof(this.parameters.email) != 'undefined') {
                        this.form_email = this.parameters.email;
                    }
                    // http://appointments-system.test/?name=yay&phone=7868058763&email=myemail@example.com
                }
            },

            FormatPhonenNumber(phone) {
                //normalize string and remove all unnecessary characters
                phone = phone.replace(/[^\d]/g, "");

                //check if number length equals to 10
                if (phone.length == 10) {
                    //reformat and return phone number
                    return '+1 ' + phone.replace(/(\d{3})(\d{3})(\d{4})/, "($1) $2-$3");

                }
                return '';
            },
        },
        mounted() {
            console.log('Component mounted.')
            this.getParamsFromUrl();
        },
        created() {
            this.getAnalystCount();
            this.loadServices();
            if (this.props_only_component) {
                this.isReadOnly = true
            }
            
        }
    }
</script>
