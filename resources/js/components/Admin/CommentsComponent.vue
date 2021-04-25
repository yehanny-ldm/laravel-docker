<template>

    <div>
        <div v-for="comment in comments">
            {{ comment.body }}
            <div class="row">
                <div class="col-md-12">
                                        <span class="float-right text-muted">
<!--                                            {{ moment(comment.created_at).format("MMM Do YY") }}-->
                                        </span>
                </div>
            </div>
            <hr>

        </div>

    </div>


</template>

<script>
// import axios from "axios";

import axios from "axios";

export default {
    name: "CommentsComponent",
    props:["postId"],
    data () {
        return {
            comments: [],
            token: this.$store.state.token,
        }
    },
    methods: {

    },
    mounted() {
        axios
            .get('/api/auth/comments/' + this.$props.postId, {
                headers: {'Authorization': "Bearer " + this.token}
            })
            .then(response => {
                this.comments = response.data
            })
    }
}
</script>

<style scoped>

</style>
