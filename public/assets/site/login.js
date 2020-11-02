function login() {
  return {
    form: {
      email: "xandecar@hotmail.com",
      password: "123",
    },
    loading: false,
    messages: {},
    async login() {
      this.loading = true;

      const formData = new FormData();
      formData.append("email", this.form.email);
      formData.append("password", this.form.password);

      try {
        const { data } = await axios.post("/login", formData);

        this.messages = data;
        this.loading = false;

        setTimeout(() => {
          window.location.href = "/";
        }, 3000);
      } catch (error) {
        this.loading = false;

        if (error.response.data["error_login"]) {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: error.response.data["error_login"],
          });
          return;
        }

        this.messages = error.response.data;
      }
    },

    message(index, type = "danger") {
      let message = {
        message: "",
        type,
      };

      if (this.messages[index]) {
        setTimeout(() => {
          this.messages[index] = "";
        }, 4000);

        message = {
          message: this.messages[index],
          type: "text text-" + type,
        };
      }

      return message;
    },
  };
}
