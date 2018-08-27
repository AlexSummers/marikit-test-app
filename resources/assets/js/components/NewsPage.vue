<template>
    <v-layout>
        <v-flex xs12 sm6 offset-sm3>
            <v-card>
                <v-card-media v-if="news.mainImageUrl"
                        class="white--text"
                        height="200px"
                        :src="news.mainImageUrl"
                >
                    <v-container fill-height fluid>
                        <v-layout fill-height>
                            <v-flex xs12 align-end flexbox>
                                <span class="headline">{{news.title}}</span>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-media>
                <v-card-title v-if="!news.mainImageUrl">
                    <div>
                        <span class="headline">{{news.title}}</span><br>
                    </div>
                </v-card-title>
                <v-card-title>
                    <div>
                        <span class="grey--text">{{news.publicationDate}}</span><br>
                    </div>
                </v-card-title>
                <v-card-text>
                    {{news.content}}
                </v-card-text>
            </v-card>
        </v-flex>
    </v-layout>
</template>

<script>
    import axios from 'axios';
    export default {
        name: "NewsPage",
        data () {
            return {
                news: {}
            }
        },
        methods: {
            loadNews() {
                let that = this;
                axios.get('/load-news/' + this.$route.params.id).then(function(response) {
                    if (response.data.status === 'success') {
                        that.news = response.data.data;
                    }
                })
            }
        },
        beforeMount() {
            this.loadNews();
        }
    }
</script>