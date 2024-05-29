import axios from "axios";
import { getServerSession } from "next-auth";
import { authOptions } from "./auth";

export const post = async (url: string, data: any, headers: any = {}) => {
  try {
    const res = await axios.post("/api/backend", {
      url,
      data,
      headers,
      method: "POST",
    });

    return {
      data: res.data,
      status: res.status,
    };
  } catch (error: any) {
    console.error(error);
    return {
      error: "Error processing request",
      status: error.response?.status || 500,
    };
  }
};

export const get = async (url: string, headers: any = {}) => {
  try {
    const res = await axios.post("/api/backend", {
      url,
      headers,
      method: "GET",
    });

    return {
      data: res.data,
      status: res.status,
    };
  } catch (error: any) {
    console.error(error);
    return {
      error: "Error processing request",
      status: error.response?.status || 500,
    };
  }
};

export const serverPost = async (url: string, data: any, headers: any = {}) => {
  try {
    const session = await getServerSession(authOptions);

    headers["Authorization"] = `Bearer ${session?.user.access_token}`;

    const res = await axios.post(process.env.BASE_URL! + url, data, {
      headers,
    });

    return {
      data: res.data,
      status: res.status,
    };
  } catch (error: any) {
    console.error(error);
    return {
      error: "Error processing request",
      status: error.response?.status || 500,
    };
  }
};

export const serverGet = async (url: string, headers: any = {}) => {
  try {
    const session = await getServerSession(authOptions);

    headers["Authorization"] = `Bearer ${session?.user.access_token}`;

    const res = await axios.get(process.env.BASE_URL! + url, {
      headers,
    });

    return {
      data: res.data,
      status: res.status,
    };
  } catch (error: any) {
    console.error(error);
    return {
      error: "Error processing request",
      status: error.response?.status || 500,
    };
  }
};
