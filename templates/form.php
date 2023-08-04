<div class="container">
    <h1 class="title">
        Cześć <?= $user->username ?>
    </h1>
    <form action="" method="post" id="app">
        <div class="field">
            <label class="label">Magiczny identyfikator {{ message }}</label>
            <div class="control">
                <input class="input is-large" type="text" placeholder="Base54" v-model="b64id">
            </div>
        </div>
    </form>
</div>


<script>

    function base64ToBytes(base64) {
        const binString = atob(base64);
        return Uint8Array.from(binString, (m) => m.codePointAt(0));
    }

    function bytesToBase64(bytes) {
        const binString = Array.from(bytes, (x) => String.fromCodePoint(x)).join("");
        return btoa(binString);
    }

    const { createApp } = Vue;

    createApp({
        data() {
            return {
                b64id: 'MTIzNDsxMTsyMDIzLTA4LTAzO1NhbXN1bmcgR2FsYXh5IDExOzEyMzQ1Njc4OTA5NzY1NDMyMQ=='
            }
        },
        computed: {
            decodedId() {

            }
        }
    }).mount('#app')
</script>
