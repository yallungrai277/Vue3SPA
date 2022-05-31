import router from "@/routes";

export default function () {
    axios.interceptors.response.use(
        function (response) {
            return response;
        },
        function (error) {
            switch (error.response.status) {
                case 404:
                    router.push({ name: "404" });
                    break;
                case 500:
                    router.push({ name: "500" });
                    break;
                case 403:
                    router.push({ name: "403" });
                    break;
                default:
            }
            return Promise.reject(error);
        }
    );
}
