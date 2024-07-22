import axios from "axios";
import { getServerSession } from "next-auth";
import { authOptions } from "./auth";

interface BackendResponse {
  data?: any;
  error?: string;
  status: number;
}

export const post = async (
  url: string,
  data: any,
  headers: any = {}
): Promise<BackendResponse> => {
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

export const put = async (
  url: string,
  data: any,
  headers: any = {}
): Promise<BackendResponse> => {
  try {
    const res = await axios.post("/api/backend", {
      url,
      data,
      headers,
      method: "PUT",
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

export const del = async (
  url: string,
  headers: any = {}
): Promise<BackendResponse> => {
  try {
    const res = await axios.post("/api/backend", {
      url,
      headers,
      method: "DELETE",
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

export const get = async (
  url: string,
  headers: any = {}
): Promise<BackendResponse> => {
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

export const serverPost = async (
  url: string,
  data: any,
  headers: any = {}
): Promise<BackendResponse> => {
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

export const serverPut = async (
  url: string,
  data: any,
  headers: any = {}
): Promise<BackendResponse> => {
  try {
    const session = await getServerSession(authOptions);

    headers["Authorization"] = `Bearer ${session?.user.access_token}`;

    const res = await axios.put(process.env.BASE_URL! + url, data, {
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

export const serverDel = async (
  url: string,
  headers: any = {}
): Promise<BackendResponse> => {
  try {
    const session = await getServerSession(authOptions);

    headers["Authorization"] = `Bearer ${session?.user.access_token}`;

    const res = await axios.delete(process.env.BASE_URL! + url, {
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

export const serverGet = async (
  url: string,
  headers: any = {}
): Promise<BackendResponse> => {
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
