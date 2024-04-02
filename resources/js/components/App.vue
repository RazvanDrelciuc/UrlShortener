<template>
    <div id="app">
        <header>
            <nav>
                <h1>URL Shortener</h1>
            </nav>
        </header>

        <main>
            <router-view></router-view>
        </main>

        <footer>
            <p>© 2024 URL Shortener Interview Task.</p>
        </footer>
    </div>
</template>

<script>
export default {
    name: 'App',
};
</script>

<style>
header {
    background-color: #007bff;
    color: white;
    padding: 10px 20px;
    text-align: center;
}

footer {
    background-color: #f8f9fa;
    color: #6c757d;
    padding: 10px 20px;
    text-align: center;
    position: fixed;
    bottom: 0;
    width: 100%;
}

main {
    padding: 20px;
    min-height: calc(100vh - 100px);
}
</style>
