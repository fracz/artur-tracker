<div class="container">
    <?php if ($user->role !== 'a'): ?>
        <form action="/confirm" method="post" id="app">
            <div class="field">
                <label class="label">Magiczny identyfikator</label>
                <div class="control">
                    <input class="input is-large" type="text" placeholder="Base54" v-model="b64id">
                </div>
            </div>
            <div v-if="decodedId">
                <div class="field is-grouped is-grouped-multiline is-centered">
                    <div class="control">
                        <div class="tags has-addons">
                            <span class="tag is-dark">Numer naprawy</span>
                            <span class="tag is-info">{{ decodedId.nrNaprawy }}</span>
                            <input type="hidden" :value="decodedId.nrNaprawy" name="nrNaprawy">
                        </div>
                    </div>
                    <div class="control">
                        <div class="tags has-addons">
                            <span class="tag is-dark">ID przyjmującego</span>
                            <span class="tag is-warning">{{ decodedId.idPrzyjmujacego }}</span>
                            <input type="hidden" :value="decodedId.idPrzyjmujacego" name="idPrzyjmujacego">
                        </div>
                    </div>
                    <div class="control">
                        <div class="tags has-addons">
                            <span class="tag is-dark">Data przyjęcia</span>
                            <span class="tag is-warning">{{ decodedId.dataPrzyjecia }}</span>
                            <input type="hidden" :value="decodedId.dataPrzyjecia" name="dataPrzyjecia">
                        </div>
                    </div>
                    <div class="control">
                        <div class="tags has-addons">
                            <span class="tag is-dark">Model</span>
                            <span class="tag is-success">{{ decodedId.model }}</span>
                            <input type="hidden" :value="decodedId.model" name="model">
                        </div>
                    </div>
                    <div class="control">
                        <div class="tags has-addons">
                            <span class="tag is-dark">SN / IMEI</span>
                            <span class="tag is-success">{{ decodedId.sn }}</span>
                            <input type="hidden" :value="decodedId.sn" name="sn">
                        </div>
                    </div>
                </div>
                <div class="has-text-centered mt-3">
                    <button type="submit" class="button is-success is-large">Dodaj naprawę</button>
                </div>
            </div>
        </form>
    <?php else: ?>
        <article class="message is-warning">
            <div class="message-header">
                <p>Jesteś administratorem</p>
            </div>
            <div class="message-body">
                Dodawanie napraw jest możliwe wyłącznie z konta z rolą Przyjmujący, Technik lub Kontrola jakości.
                Możesz przeglądać listę napraw.
            </div>
        </article>
    <?php endif; ?>
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

    const {createApp} = Vue;

    createApp({
        data() {
            return {
                b64id: 'MTIzNDsxMTsyMDIzLTA4LTAzO1NhbXN1bmcgR2FsYXh5IDExOzEyMzQ1Njc4OTA5NzY1NDMyMQ=='
                // b64id: ''
            }
        },
        computed: {
            decodedId() {
                const decoded = atob(this.b64id);
                const parts = decoded.split(';');
                if (parts.length === 5) {
                    [nrNaprawy, idPrzyjmujacego, dataPrzyjecia, model, sn] = parts;
                    return {nrNaprawy, idPrzyjmujacego, dataPrzyjecia, model, sn};
                } else {
                    return undefined;
                }
            }
        }
    }).mount('#app')
</script>
