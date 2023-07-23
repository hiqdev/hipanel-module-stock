import ky from "ky";

const request = ky.extend({
  hooks: {
    beforeRequest: [
      request => {
        if (request.method === "POST") {
          const token = document.querySelector("meta[name=\"csrf-token\"]").getAttribute("content");
          request.headers.set("X-CSRF-Token", token);
        }
      },
    ],
  },
});

const api = {
  getSessions: async () => await request.get("get-sessions").json(),
  createSession: async () => await request.post("create-session").json(),
  getUser: async () => await request.get("get-user").json(),
  getLocations: async () => await request.get("get-locations").json(),
  getTasks: async () => await request.get("get-tasks").json(),
  resolveCode: async (code, location) => await request.post("resolve-code", {
    searchParams: {
      code, location,
    },
  }).json(),
};

export default api;
