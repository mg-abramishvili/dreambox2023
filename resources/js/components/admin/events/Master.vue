<template>
    <div>
        <div class="content-header px-4">
            <h1>
                <router-link :to="{name: 'Events'}" class="btn btn-back">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-short" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z"/>
                    </svg>
                </router-link>

                <template v-if="$route.params.id">
                    {{ event.name }}
                </template>
                <template v-else>
                    Новое событие
                </template>
            </h1>
        </div>

        <div v-if="views.loading" class="p-4">
            <Loader />
        </div>

        <div v-if="!views.loading" class="content p-4">
            <div class="row">
                <div class="col-6">
                    <div class="mb-4">
                        <label class="form-label">Название</label>
                        <input v-model="name" type="text" class="form-control">
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-4">
                        <label class="form-label">Дата</label>
                        <input v-model="date" type="date" class="form-control">
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-4">
                        <label class="form-label">Время</label>
                        <input v-model="time" type="text" class="form-control">
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Описание</label>
                <ckeditor :editor="editor" v-model="description" :config="editorConfig"></ckeditor>
            </div>

            <div class="mb-4">
                <label class="form-label">Постер</label>
                <file-pond
                    name="event_image"
                    ref="event_image"
                    label-idle="Выбрать файл"
                    v-bind:allow-multiple="false"
                    v-bind:allow-reorder="false"
                    accepted-file-types="image/jpeg, image/png"
                    :server="server"
                    v-bind:files="filepond_content_edit"
                />
            </div>

            <button @click="save()" :disabled="!views.saveButton" class="btn btn-primary">Сохранить</button>
            <button @click="del()" v-if="$route.params.id" class="btn btn-outline-danger ms-2">Удалить</button>
        </div>

    </div>
</template>

<script>
import vueFilePond from "vue-filepond"
import "filepond/dist/filepond.min.css"
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css"
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type"
import FilePondPluginImagePreview from "filepond-plugin-image-preview"

const FilePond = vueFilePond(
  FilePondPluginFileValidateType,
  FilePondPluginImagePreview
)

import CKEditor from '@ckeditor/ckeditor5-vue'
import ClassicEditor from '@ckeditor/ckeditor5-build-classic'

export default {
    data() {
        return {
            event: {},

            name: '',
            date: '',
            time: '',
            description: '',
            image: '',

            filepond_content: [],
            filepond_content_edit: [],

            views: {
                loading: true,
                saveButton: true,
            },

            editor: ClassicEditor,
            editorConfig: {
                toolbar: [ 'bold', 'italic', '|', 'bulletedList', 'numberedList', '|', 'insertTable', '|', 'undo', 'redo' ],
            },

            server: {
                remove(filename, load) {
                    load('1');
                },
                process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                    const formData = new FormData();
                    formData.append(fieldName, file, file.name);
                    const request = new XMLHttpRequest();
                    request.open('POST', '/api/admin/file/upload');
                    request.upload.onprogress = (e) => {
                        progress(e.lengthComputable, e.loaded, e.total);
                    };
                    request.onload = function() {
                        if (request.status >= 200 && request.status < 300) {
                            load(request.responseText);
                        }
                        else {
                            error('oh no');
                        }
                    };
                    request.send(formData);
                    return {
                        abort: () => {
                            request.abort();
                            abort();
                        }
                    };
                },
                revert: (filename, load) => {
                    load(filename)
                },
                load: (source, load, error, progress, abort, headers) => {
                    var myRequest = new Request(source);
                    fetch(myRequest).then(function(response) {
                        response.blob().then(function(myBlob) {
                            load(myBlob)
                        });
                    });
                },
            },
        }
    },
    created() {
        if(this.$route.params.id) {
            this.loadEvent()
        }

        if(!this.$route.params.id) {
            this.views.loading = false
        }
    },
    methods: {
        loadEvent() {
            axios.get(`/api/admin/event/${this.$route.params.id}`)
            .then(response => {
                this.event = response.data

                this.name = response.data.name
                this.date = response.data.date
                this.time = response.data.time
                this.description = response.data.description

                if(response.data.image) {
                    this.filepond_content_edit = [
                        {
                            source: response.data.image,
                            options: {
                                type: 'local',
                            }
                        }
                    ]
                }

                this.views.loading = false
            })
        },
        save() {
            if(document.getElementsByName("event_image")[0]) {
                this.image = document.getElementsByName("event_image")[0].value
            }

            if(!this.name) {
                return this.$swal({
                    text: 'Укажите название',
                    icon: 'error',
                })
            }

            if(!this.image) {
                return this.$swal({
                    text: 'Загрузите постер',
                    icon: 'error',
                })
            }

            if(!this.date) {
                return this.$swal({
                    text: 'Укажите дату',
                    icon: 'error',
                })
            }

            if(!this.time) {
                return this.$swal({
                    text: 'Укажите время',
                    icon: 'error',
                })
            }

            if(!this.description) {
                return this.$swal({
                    text: 'Заполните описание',
                    icon: 'error',
                })
            }

            this.views.saveButton = false

            if(this.$route.params.id) {
                axios.put(`/api/admin/event/${this.$route.params.id}/update`, {
                    name: this.name,
                    date: this.date,
                    time: this.time,
                    description: this.description,
                    image: this.image,
                })
                .then(response => {
                    this.views.saveButton = true
    
                    this.$router.push({ name: 'Events' })
                })
                .catch(errors => {
                    this.views.saveButton = true
                    
                    return this.$swal({
                        text: 'Ошибка',
                        icon: 'error',
                    })
                })
            }

            if(!this.$route.params.id) {
                axios.post(`/api/admin/events`, {
                    name: this.name,
                    date: this.date,
                    time: this.time,
                    description: this.description,
                    image: this.image,
                })
                .then(response => {
                    this.views.saveButton = true
    
                    this.$router.push({ name: 'Events' })
                })
                .catch(errors => {
                    this.views.saveButton = true
                    
                    return this.$swal({
                        text: 'Ошибка',
                        icon: 'error',
                    })
                })
            }
        },
        del() {
            if(confirm('Точно удалить событие?')) {
                axios.delete(`/api/admin/event/${this.$route.params.id}/delete`)
                .then(response => {
                    this.$router.push({ name: 'Events' })
                })
                .catch(errors => {
                    return this.$swal({
                        text: 'Ошибка',
                        icon: 'error',
                    })
                })
            }
        },
    },
    components: {
        FilePond,
        ckeditor: CKEditor.component,
    }
}
</script>