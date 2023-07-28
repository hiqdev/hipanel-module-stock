import ky from "ky";

const request = ky.extend({
  timeout: 50000, hooks: {
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
  // GET
  getSessions: async () => await request.get("get-sessions").json(),
  getUser: async () => await request.get("get-user").json(),
  getLocations: async () => await request.get("get-locations").json(),
  // POST
  createSession: async () => await request.post("create-session").json(),
  setSession: async (id, state) => await request.post("set-session", {
    searchParams: { id },
    json: state,
  }).json(),
  deleteSession: async (id) => await request.post("delete-session", { searchParams: { id } }).json(),
  resolveCode: async (code, location) => await request.post("resolve-code", {
    searchParams: { code, location },
  }).json(),
  complete: async (payload) => await request.post("complete", { json: payload }).json(),
};

export default api;
