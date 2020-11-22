<template>
    <div>
        <div class="add_block">
            <div class="add">
                <a-button type="primary" @click="addNote()">
                    Add
                </a-button>
            </div>
            <div v-if="add_note" class="row">
                <div class="col-md-3">
                    <a-input v-model="new_note.name" placeholder="Name" />
                </div>

                <div class="col-md-3">
                    <a-textarea
                        v-model="new_note.text"
                        placeholder="Controlled autosize"
                        :auto-size="{ minRows: 3, maxRows: 5 }"
                    />
                </div>

                <div class="col-md-3">
                    <a-upload
                        name="image"
                        list-type="picture-card"
                        class="img-uploader"
                        :show-upload-list="false"
                        action="image-upload"
                        :headers="headers"
                        :before-upload="beforeUpload"
                        @change="normFile"
                    >
                        <img v-if="imageUrl" :src="imageUrl" alt="avatar" />
                        <div v-else>
                            <a-icon :type="loading ? 'loading' : 'plus'" />
                            <div class="ant-upload-text">
                                Upload
                            </div>
                        </div>
                    </a-upload>
                </div>
                <div class="col-md-1">
                    <a-button type="primary" @click="saveNote()">
                        save
                    </a-button>
                </div>
            </div>
        </div>

        <div class="notes_block" v-if="notes.length">
            <div class="row" v-for="note in notes">
                <div class="col-md-3">
                    <div v-html="note.name"></div>
                </div>
                <div class="col-md-3">
                    <p v-html="note.text"></p>
                </div>
                <div class="col-md-3">
                    <img :src="'/images/notes/'+note.img" alt="">
                </div>
                <div class="col-md-1">
                    <a-button type="danger" @click="removeNote(note)">
                        remove
                    </a-button>
                </div>
            </div>
        </div>
        <div class="pagination">
            <a-pagination v-if="paginate.total" @change="onChangePagination" v-model="paginate.current" :defaultPageSize="paginate.per_page" :total="paginate.total" show-less-items />
        </div>

    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: "Notes",
    data() {
        return {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
            },
            add_note: false,
            loading: false,
            file: '',
            paginate: {
                current: '',
                total: '',
                per_page: ''
            },
            notes: [],
            imageUrl: '',
            new_note: {
                name: '',
                text: '',
                img: ''
            }
        }
    },
    mounted() {
        this.getNotes();
    },
    methods: {
        onChangePagination(pag) {
            this.getNotes(pag);
        },
        getBase64(img, callback) {
            const reader = new FileReader();
            reader.addEventListener('load', () => callback(reader.result));
            reader.readAsDataURL(img);
        },
        getNotes(pag = false) {
            let url = '/get-notes';
            if (pag) {
                url = '/get-notes?page='+pag;
            }
            axios.post(url
            ).then((e) => {
                console.log(e);
                if (e.status === 200) {
                    this.notes = e.data.data.data;
                    this.paginate.current = e.data.data.current_page;
                    this.paginate.per_page = e.data.data.per_page;
                    this.paginate.total = e.data.data.total;
                    console.log(this.notes);
                }
            });
        },
        addNote() {
            this.add_note = true;
        },
        saveNote() {
            axios.post('/add-note',
                this.new_note,
            ).then((e) => {
                console.log(e);
                this.new_note = {
                    name: '',
                    text: '',
                    img: ''
                };
                this.add_note = false;
                this.getNotes();
            });
        },
        removeNote(note) {
            axios.post('/remove-note',
                note,
            ).then((e) => {
                this.getNotes();
            });
        },
        beforeUpload(file) {
            const isJpgOrPng = file.type === 'image/jpeg' || file.type === 'image/png';
            if (!isJpgOrPng) {
                this.$message.error('You can only upload JPG file!');
            }
            const isLt2M = file.size / 1024 / 1024 < 2;
            if (!isLt2M) {
                this.$message.error('Image must smaller than 2MB!');
            }
            return isJpgOrPng && isLt2M;
        },
        normFile(info) {
            if (info.file.status === 'uploading') {
                this.loading = true;
                return;
            }
            if (info.file.status === 'done') {
                this.getBase64(info.file.originFileObj, imageUrl => {
                    this.imageUrl = imageUrl;
                    console.log(info.file.originFileObj);
                    this.new_note.img = info.file.originFileObj.name;
                    this.loading = false;
                    console.log(this.imageUrl)
                });
            }
        },
    }

}
</script>

<style lang="scss">
@import "~ant-design-vue/dist/antd.css";

.add {
    margin-bottom: 30px;
}

img {
    max-width: 200px
}
</style>
