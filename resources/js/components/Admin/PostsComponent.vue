<template>

    <div class="container">

        <div class="modal fade" id="createPost" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Nuevo Post</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div v-show="modal" class="alert alert-warning" role="alert">
                            Ha ocurrido un error al crear Post
                        </div>
                        <form>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">T&iacute;tulo:</label>
                                <input v-model="title" type="text" class="form-control" id="recipient-name">
                            </div>
                            <div class="form-group">
                                <label for="message-text" class="col-form-label">Content:</label>
                                <textarea v-model="content" class="form-control" id="message-text"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" @click="savePost" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        <SpinnerComponent v-show="loading"></SpinnerComponent>

        <button v-if="token" type="button" class="btn btn-primary float-right"
                data-toggle="modal" data-target="#createPost">+ Add
            post
        </button>

        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content">
                <li class="page-item">
                    <a class="page-link" v-show="current_page > 1" @click="changePage(1)" tabindex="-1"
                       aria-disabled="true">First</a>
                </li>
                <li class="page-item" @click="changePage(current_page - 1)" v-show="current_page > 1"><a
                    class="page-link" href="#">{{ current_page - 1 }}</a></li>
                <li class="page-item disabled"><a class="page-link" href="#">{{ current_page }}</a></li>
                <li class="page-item" @click="changePage(current_page + 1)" v-show="current_page + 1 <= last_page"><a
                    class="page-link" href="#">{{ current_page + 1 }}</a></li>
                <li class="page-item">
                    <a class="page-link" v-show="current_page !== last_page" @click="changePage(last_page)">Final</a>
                </li>
            </ul>
        </nav>
        <div class="row row-cols-1 row-cols-md-2 g-4">

            <div class="col" v-for="post in filteredResources" :key="post.id">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">{{ post.title }}</h5>
                        <p class="card-text">{{ post.content }}</p>
                        <p class="card-text">
                            <small class="text-muted">
                                {{ moment(post.created_at,"DD-MM-YYYY").fromNow() }}
                            </small>
                        </p>
                        <router-link
                            class="btn btn-outline-primary btn-block"
                            :to="{
                          name: 'PostDetails',
                          params: { slug: post.slug }
                        }">
                            Ver m&aacute;s
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import moment from "moment";
    import axios from 'axios';

    export default {
        name: "Posts",
        data() {
            return {
                moment: moment,
                posts: [],
                loading: true,
                current_page: null,
                last_page: null,
                token: this.$store.state.token,
                title: '',
                content: '',
                modal: false
            }
        },
        methods: {
            changePage(page) {
                axios
                    .get('/api/posts/?page=' + page)
                    .then(response => {
                        this.loading = false
                        this.posts = response.data.data
                        this.current_page = response.data.current_page
                        this.last_page = response.data.last_page
                        console.log(response.data)
                    })
            },
            savePost() {
                axios
                    .post('api/auth/posts', {
                        "title": this.title,
                        "content_post": this.content

                    }, {
                        headers: {'Authorization': "Bearer " + this.token}
                    })
                    .then(response => {
                        if (error.response !== 200) {
                            this.modal = true
                        }
                        console.log(response.data)
                    }).catch(function (error) {
                        this.modal = true
                        console.log(response.data)

                    }
                );
            }
        },
        created() {
            axios
                .get('/api/posts')
                .then(response => {
                    this.loading = false
                    this.posts = response.data.data
                    this.current_page = response.data.current_page
                    this.last_page = response.data.last_page
                    console.log(response.data)
                })
            console.log(this.posts)
        },
        computed: {
            filteredResources() {
                if (this.searchQuery) {
                    console.log(this.posts.filter(p => console.log(p.title)))
                    return this.posts.filter((p) => p.title == this.searchQuery);
                } else {
                    return this.posts;
                }
            }
        }
    }
</script>

<style scoped>
    a {
        cursor: pointer;
    }

    .card:hover {
        transform: scale(1.05);
        box-shadow: 0 10px 20px rgba(0, 0, 0, .12), 0 4px 8px rgba(0, 0, 0, .06);
    }
</style>
