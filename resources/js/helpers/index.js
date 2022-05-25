import LocalStorage from "./localstorage";

const setBearerToken = (token) => {
    const storage = new LocalStorage();
    if (!token) {
        storage.removeItem("authToken");
        return (axios.defaults.headers.common["Authorization"] = "Bearer ");
    }

    storage.setItem("authToken", token);
    return (axios.defaults.headers.common["Authorization"] = "Bearer " + token);
};

export { setBearerToken };
