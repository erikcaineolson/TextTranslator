<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <section v-if="loading">
                    <img src="/img/spinning-globe.gif" alt="Loading...">
                </section>

                <section v-else-if="submitted">
                    <div class="card">
                        <div class="card-header bg-danger text-white" v-if="failed">
                            <h1 class="card-title">Oops!</h1>
                        </div>
                        <div class="card-header bg-success text-white" v-else>
                            <h1 class="card-title">Your Translation</h1>
                        </div>

                        <div class="card-body bg-light">
                            <section v-if="failed">
                                <p>{{ failureMessage }}</p>
                                <p>
                                    <button class="btn btn-block btn-success" v-on:click="tryAgain">Try Again</button>
                                </p>
                            </section>
                            <section v-else>
                                <p>
                                    When you uploaded your file, we made a call to Google's Cloud Translation service. The service determined the source language of the file as
                                    {{ sourceLanguage }}, and translated your document into {{ desiredLanguage }}. If you're interested, you can
                                    <a href="{{ route('stats.index') }}">view usage here</a>.
                                </p>
                                <p class="text-white bg-dark">
                                    {{ translatedText }}
                                </p>
                                <p>
                                    <button class="btn btn-block btn-success" v-on:click="tryAgain">Do it Again</button>
                                </p>
                            </section>
                        </div>
                    </div>
                </section>

                <section v-else>
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h1 class="card-title">File Translator</h1>
                        </div>

                        <div class="card-body bg-light">
                            <p>
                                Select a language and upload a file below to translate your file into the desired language.
                                Downloads will only be available as long as you keep this page open, for up to 24 hours, for your protection.
                                If you're interested, you can <a href="/stats">view usage here</a>.
                            </p>
                            <p>
                                <strong><em>Note: these files ARE NOT encrypted. DO NOT submit sensitive data, like banking or other personal information, to this app.</em></strong>
                            </p>
                            <form @submit="getTranslatedFile" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="chosenLanguage">Desired Output Language:</label>
                                    <select class="form-control" id="chosenLanguage" ref="chosenLanguage" v-model="languages" required>
                                        <option v-for="language in languages" v-bind:value="language.code">{{ language.moniker }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="initialFile">Your File</label>
                                    <input accept="text/plain" class="form-control-file" id="initialFile" ref="initialFile" type="file" required v-on:change="handleFileUpload">
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-block btn-primary text-white">Upload File</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "TextTranslator",
        data() {
            return {
                chosenLanguage: null,
                desiredLanguage: '',
                initialFile: null,
                failed: false,
                failureMessage: '',
                loading: true,
                sourceLanguage: '',
                submitted: false,
                translatedText: ''
            }
        },
        methods: {
            getTranslatedFile(evt) {
                evt.preventDefault();

                let textTranslator = this,
                    formData = new FormData();

                textTranslator.loading = true;
                formData.append('initialFile', this.initialFile);
                formData.append('chosenLanguage', this.languages);

                axios.post('/api/translations', formData, {
                    'headers': {
                        'Content-type': 'multipart/form-data'
                    }
                })
                    .then(function (rsp) {
                        textTranslator.failed = true;
                        textTranslator.failureMessage = rsp.data.msg;

                        if (rsp.data.rslt === 'success') {
                            textTranslator.failed = false;
                            textTranslator.failureMessage = '';
                            textTranslator.submitted = true;

                            textTranslator.desiredLanguage = rsp.data.desiredLanguage;
                            textTranslator.sourceLanguage = rsp.data.sourceLanguage;
                            textTranslator.translatedText = rsp.data.msg;
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                        textTranslator.failed = true;
                        textTranslator.failureMessage = error;
                        textTranslator.submitted = true;
                    })
                    .finally(function () {
                        textTranslator.loading = false;
                        textTranslator.loadLanguages();
                    });
            },

            handleFileUpload() {
                this.initialFile = this.$refs.initialFile.files[0];
            },

            loadLanguages() {
                axios.get('/api/translations')
                    .then(response => (this.languages = response.data))
                    .catch(error => {
                        console.log(error);
                        this.failed = true;
                    })
                    .finally(() => this.loading = false);
            },

            tryAgain() {
                this.loadLanguages();
                this.failed = false;
                this.submitted = false;
            }
        },
        mounted() {
            this.loadLanguages();
        },
        props: ['data'],
    }
</script>

<style scoped>

</style>
