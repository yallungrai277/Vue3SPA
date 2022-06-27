import LocalStorage from "./localstorage";

const storage = new LocalStorage();
const setBearerToken = (token) => {
    if (!token) {
        storage.removeItem("authToken");
        return (axios.defaults.headers.common["Authorization"] = "Bearer ");
    }

    storage.setItem("authToken", token);
    return (axios.defaults.headers.common["Authorization"] = "Bearer " + token);
};

const setRefreshToken = (token) => {
    if (!token) {
        return storage.removeItem("refreshToken");
    }
    return storage.setItem("refreshToken", token);
};

export { setBearerToken, setRefreshToken };
