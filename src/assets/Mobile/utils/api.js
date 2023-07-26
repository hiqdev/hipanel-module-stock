import ky from "ky";

const request = ky.extend({
  timeout: 50000,
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
  setSession: async () => await request.get("set-session").json(),
  getUser: async () => await request.get("get-user").json(),
  getLocations: async () => await request.get("get-locations").json(),

  createSession: async () => await request.post("create-session").json(),
  deleteSession: async (sessionId) => await request.post("delete-session", {
    searchParams: {
      sessionId,
    },
  }).json(),
  resolveCode: async (code, location) => await request.post("resolve-code", {
    searchParams: {
      code, location,
    },
  }).json(),
  move: async (payload) => await request.post("move", { json: payload }).json(),
  sendMessage: async (payload) => await request.post("send-message", { json: payload }).json(),
};

export default api;
