export default class LocalStorage {
    setItem(key, value) {
        localStorage.setItem(key, value);
        return;
    }

    getItem(key) {
        return localStorage.getItem(key);
    }

    removeItem(key) {
        localStorage.removeItem(key);
        return;
    }
}
