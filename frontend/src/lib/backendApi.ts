import axios from "axios";

export const post = async (url: string, data: any, headers: any = {}) => {
  try {
    const res = await axios.post("/api/backend", {
      url,
      data,
      headers,
      method: "POST",
    });

    return res.data;
  } catch (error: any) {
    console.error(error);
    return { error: "Error processing request" };
  }
};

export const get = async (url: string, headers: any = {}) => {
  try {
    const res = await axios.post("/api/backend", {
      url,
      headers,
      method: "GET",
    });

    return res.data;
  } catch (error: any) {
    console.error(error);
    return { error: "Error processing request" };
  }
};
