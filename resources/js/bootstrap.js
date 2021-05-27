window._ = require('lodash');
window.axios = require('axios');
window.Vue = require('vue');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}
$(function () {
    $('[data-toggle="tooltip"]').tooltip();
});
if($('#phoneVerification').length) {
    const app = new Vue({
        el: '#phoneVerification',
        data: {
            phone: phone,
            code: null,
            sent: false,
            countDown: 60,
        },
        methods: {
            sendCode() {
                axios.post(baseUrl + '/ajax/phone', {
                    phone: this.phone,
                    op: 'sendCode'
                })
                    .then((response) => {
                        if (response.data.status) {
                            Swal.fire('عملیات موفق !', response.data.msg, 'success');
                            this.sent = true;
                            this.countDownTimer(true);
                            this.countDownTimer();
                        } else {
                            Swal.fire('خطا !', response.data.msg, 'error');
                            this.sent = false;
                        }
                    }, (error) => {
                        Swal.fire('خطا !', 'مشکلی پیش آمده , لطفا مجددا تلاش کنید !', 'error');
                        this.sent = false;
                    });
            },
            verifyCode() {
                axios.post(baseUrl + '/ajax/phone', {
                    code: this.code,
                    op: 'verifyPhone'
                })
                    .then((response) => {
                        if (response.data.status) {
                            Swal.fire('عملیات موفق !', response.data.msg, 'success');
                            this.sent = false;
                        } else {
                            Swal.fire('خطا !', response.data.msg, 'error');
                            this.sent = true;
                        }
                    }, (error) => {
                        Swal.fire('خطا !', 'مشکلی پیش آمده , لطفا مجددا تلاش کنید !', 'error');
                        this.sent = true;
                    });
            },
            countDownTimer(set = false) {
                if (set) {
                    this.countDown = 60;
                } else {
                    if (this.countDown > 0) {
                        setTimeout(() => {
                            this.countDown -= 1
                            this.countDownTimer()
                        }, 1000)
                    }
                }
            }
        }
    });
}

if($('#contacts').length){
    const contacts = new Vue({
        el: '#contacts',
        data:{
            contacts:contactsList,
            id:null,
            name:null,
            targetId:null,
            phone:null,
            numbers:[]
        },
        methods:{
            update(event){
                let name = event.target.value;
                let id = event.target.offsetParent.dataset.id;
                let parent = event.target.offsetParent;
                this.name = name;
                this.id = id;
                let text = this.$refs['text_'+id][0];
                let input = this.$refs['input_'+id][0];
                if(input.value.trim() == text.textContent.trim()){
                    text.style.display = 'block';
                    input.style.display = 'none';
                    parent.classList.remove('active');
                }else {
                    axios.patch(baseUrl + '/contacts/update', {
                        name:name,
                        id:id
                    }).then((response) => {
                        if(response.data.status){
                            input.style.display = 'none';
                            text.innerText = name;
                            text.style.display = 'block';
                            parent.classList.remove('active');
                            Swal.fire('تبریک !', 'با موفقیت ویرایش شد !', 'success');
                        }else {
                            Swal.fire('خطا !', response.data.msg, 'error');
                        }
                    });
                }
            },
            edit(event){
                let parent = event.target.offsetParent;
                let id = parent.dataset.id;
                this.id = id;
                parent.classList.add('active');
                this.$refs['input_'+id][0].style.display = 'block';
                this.$refs['input_'+id][0].focus();
                this.$refs['text_'+id][0].style.display = 'none';
            },
            add(){
                Swal.fire({
                    title: 'نام دفترچه را وارد کنید',
                    input: 'text',
                    inputAttributes: {
                        autocapitalize: 'off'
                    },
                    showCancelButton: true,
                    confirmButtonText: 'تایید',
                    cancelButtonText: 'لغو',
                    showLoaderOnConfirm: true,
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.value) {
                        this.name = result.value;
                        axios.post(baseUrl + '/contacts/store', {
                            name:result.value
                        }).then((response) => {
                            if(response.data.status){
                                this.id = response.data.id;
                                this.contacts.push({
                                    id:this.id,
                                    name:this.name
                                });
                            }else {
                                Swal.fire('خطا !', response.data.msg, 'error');
                            }
                        });
                    }
                })
            },
            normal(event){
                let id = this.id;
                if(id){
                    this.$refs['item_'+id][0].classList.remove('active');
                    this.$refs['input_'+id][0].style.display = 'none';
                    this.$refs['text_'+id][0].style.display = 'block';
                }
            },
            destroy(event){
                let parent = event.target.offsetParent;
                let id = parent.dataset.id;
                Swal.fire({
                    title: 'آیا از اقدام خود مطمئنید ؟',
                    text: "این دفترچه و تمام شماره های ثبت شده و رویداد های آن حذف خواهد شد !",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'بله , پاک کن !',
                    cancelButtonText: 'خیر',
                }).then((result) => {
                    if (result.value) {
                        axios.delete(baseUrl + '/contacts/delete', {
                            data:{id:id}
                        }) .then((response) => {
                            if(response.data.status){

                                parent.parentNode.removeChild(parent);
                                Swal.fire('عملیات موفق !', response.data.msg, 'success');
                            }else {
                                Swal.fire('خطا !', response.data.msg, 'error');
                            }
                        }, (error) => {
                            Swal.fire('خطا !', 'مشکلی پیش آمده , لطفا مجددا تلاش کنید !', 'error');
                            this.sent = true;
                        });
                    }
                })
            },
            addPhone(){
                axios.post(baseUrl + '/contacts/add', {
                    phone:this.phone,
                    id:this.targetId
                }).then((response) => {
                    if(response.data.status){
                        Swal.fire('تبریک !', response.data.msg, 'success');
                        this.phone = '';
                    }else {
                        Swal.fire('خطا !', response.data.msg, 'error');
                    }
                });
            },
            show(event){
                let parent = event.target.offsetParent;
                let id = parent.dataset.id;
                axios.post(baseUrl + '/contacts/numbers', {
                    id:id
                }).then((response) => {
                    if(response.data.status){
                        this.numbers = response.data.numbers
                    }else {
                        Swal.fire('خطا !', response.data.msg, 'error');
                    }
                });
                $("#myModal").modal('show');
            }
        }
    });
}

if($('#events').length){
    const contacts = new Vue({
        el: '#events',
        data:{
            events:eventsList,
            contacts:contactsList,
            contact_id:'',
            year:'',
            hour:'',
            minute:'',
            day:'',
            month:'',
            msg:'',
            from:'',
            working:false,
        },
        methods:{
            destroy(event){
                let parent = event.target.offsetParent;
                let id = parent.dataset.id;
                Swal.fire({
                    title: 'آیا از اقدام خود مطمئنید ؟',
                    text: "این رویداد پاک خواهد شد و پیامک ها ارسال نخواهند شد !",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'بله , پاک کن !',
                    cancelButtonText: 'خیر',
                }).then((result) => {
                    if (result.value) {
                        axios.delete(baseUrl + '/events/delete', {
                            data:{id:id}
                        }) .then((response) => {
                            if(response.data.status){
                                parent.parentNode.removeChild(parent);
                                Swal.fire('عملیات موفق !', response.data.msg, 'success');
                            }else {
                                Swal.fire('خطا !', response.data.msg, 'error');
                            }
                        }, (error) => {
                            Swal.fire('خطا !', 'مشکلی پیش آمده , لطفا مجددا تلاش کنید !', 'error');
                        });
                    }
                })
            },
            addEvent(){
                this.working = false;
                axios.post(baseUrl + '/events/store', {
                    year:this.year,
                    hour:this.hour,
                    minute:this.minute,
                    day:this.day,
                    month:this.month,
                    msg:this.msg,
                    contact_id:this.contact_id,
                    from:this.from
                }).then((response) => {
                    if(response.data.status){
                        Swal.fire('تبریک !', response.data.msg, 'success');
                        console.log(JSON.parse(response.data.object));
                        this.events.push(JSON.parse(response.data.object));
                    }else {
                        Swal.fire('خطا !', response.data.msg, 'error');
                    }
                    this.working = false;
                }).catch((err)=>{
                    Swal.fire('خطا !', 'خطایی پیش آمده , لطفا صفحه را دوباره بارگیری کنید و مجددا تلاش کنید یا با پشتیبانی تماس بگیرید', 'error');
                    this.working = false;
                });
            },
            status(item){
                if(item.status == 'pending'){
                    return 'در انتظار اجرا'
                }else if(item.status == 'failed'){
                    return 'شکست خورده'
                }else {
                    return 'انجام شده'
                }
            }
        }
    });
}

if($('#resetPass').length) {
    const app = new Vue({
        el: '#resetPass',
        data: {
            phone: '',
            code: null,
            sent: false,
            countDown: 60,
        },
        methods: {
            sendCode() {
                axios.post(baseUrl + '/ajax/reset', {
                    phone: this.phone,
                    op: 'sendCode'
                })
                    .then((response) => {
                        if (response.data.status) {
                            Swal.fire('عملیات موفق !', response.data.msg, 'success');
                            this.sent = true;
                            this.countDownTimer(true);
                            this.countDownTimer();
                        } else {
                            Swal.fire('خطا !', response.data.msg, 'error');
                            this.sent = false;
                        }
                    }, (error) => {
                        Swal.fire('خطا !', 'مشکلی پیش آمده , لطفا مجددا تلاش کنید !', 'error');
                        this.sent = false;
                    });
            },
            verifyCode() {
                axios.post(baseUrl + '/ajax/reset', {
                    code: this.code,
                    op: 'verifyPhone'
                })
                    .then((response) => {
                        if (response.data.status) {
                            Swal.fire('عملیات موفق !', response.data.msg, 'success');
                            this.sent = false;
                        } else {
                            Swal.fire('خطا !', response.data.msg, 'error');
                            this.sent = true;
                        }
                    }, (error) => {
                        Swal.fire('خطا !', 'مشکلی پیش آمده , لطفا مجددا تلاش کنید !', 'error');
                        this.sent = true;
                    });
            },
            countDownTimer(set = false) {
                if (set) {
                    this.countDown = 60;
                } else {
                    if (this.countDown > 0) {
                        setTimeout(() => {
                            this.countDown -= 1
                            this.countDownTimer()
                        }, 1000)
                    }
                }
            }
        }
    });
}
