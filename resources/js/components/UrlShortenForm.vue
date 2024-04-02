<template>
    <div class="url-shorten-form">
        <form @submit.prevent="submitUrl">
            <div class="input-group">
                <input
                    type="url"
                    v-model="url"
                    class="url-input"
                    placeholder="Enter URL to shorten"
                    required
                />
                <button type="submit" class="submit-btn">Shorten</button>
            </div>
        </form>
        <div v-if="errorMessage !== null" class="result-section">
            <p>{{ errorMessage }}</p>
        </div>
        <div v-if="safetyMessage !== null" class="result-section">
            <p>{{ safetyMessage }}</p>
        </div>
        <div v-if="shortUrl !== null" class="result-section">
            <p>Short URL: <a :href="shortUrl" target="_blank">{{ shortUrl }}</a></p>
            <button @click="copyToClipboard">Copy to Clipboard</button>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'UrlShortenForm',
    data() {
        return {
            url: '',
            shortUrl: null,
            errorMessage: null,
            safetyMessage: null,
        };
    },
    methods: {
        async submitUrl() {
            try {
                const response = await axios.post('/api/shorten', { original_url: this.url });
                this.shortUrl = response.data.shortUrl;
                this.errorMessage = response.data.error || null;
                this.safetyMessage = response.data.safetyMessage || null;
            } catch (error) {
                this.errorMessage = 'An error occurred while shortening the URL.';
                this.safetyMessage = null;
            }
        },
        copyToClipboard() {
            navigator.clipboard.writeText(this.shortUrl).then(() => {
                alert('URL copied to clipboard!');
            }, (err) => {
                console.error('Could not copy text: ', err);
            });
        }
    },
};
</script>

<style scoped>
.url-shorten-form {
    max-width: 500px;
    margin: auto;
    text-align: center;
}

.input-group {
    display: flex;
    margin-bottom: 20px;
}

.url-input {
    flex-grow: 1;
    padding: 10px;
    border: 1px solid #ccc;
    border-right: none;
}

.submit-btn {
    padding: 10px 20px;
    border: none;
    background-color: #007bff;
    color: white;
    cursor: pointer;
}

.result-section {
    margin-top: 20px;
}

.result-section p {
    margin: 10px 0;
}
</style>
