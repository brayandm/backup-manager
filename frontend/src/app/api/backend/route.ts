import { authOptions } from "@/lib/auth";
import axios from "axios";
import { getServerSession } from "next-auth";
import { NextRequest, NextResponse } from "next/server";

export async function POST(req: NextRequest) {
  const request = await req.json();

  const session = await getServerSession(authOptions);

  if (request.method === "POST") {
    try {
      const { url, data, headers } = request;

      headers["Authorization"] = `Bearer ${session?.user.access_token}`;

      const res = await axios.post(process.env.BASE_URL! + url, data, {
        headers,
      });

      return NextResponse.json(res.data, { status: res.status });
    } catch (error: any) {
      console.error(error);

      return NextResponse.json(
        { error: error.response?.data || "Error processing request" },
        { status: error.response?.status || 500 }
      );
    }
  } else if (request.method === "PUT") {
    try {
      const { url, data, headers } = request;

      headers["Authorization"] = `Bearer ${session?.user.access_token}`;

      const res = await axios.put(process.env.BASE_URL! + url, data, {
        headers,
      });

      return NextResponse.json(res.data, { status: res.status });
    } catch (error: any) {
      console.error(error);

      return NextResponse.json(
        { error: error.response?.data || "Error processing request" },
        { status: error.response?.status || 500 }
      );
    }
  } else if (request.method === "GET") {
    try {
      const { url, headers } = request;

      headers["Authorization"] = `Bearer ${session?.user.access_token}`;

      const res = await axios.get(process.env.BASE_URL! + url, {
        headers,
      });

      return NextResponse.json(res.data, { status: res.status });
    } catch (error: any) {
      console.error(error);

      return NextResponse.json(
        { error: error.response?.data || "Error processing request" },
        { status: error.response?.status || 500 }
      );
    }
  } else if (request.method === "DELETE") {
    try {
      const { url, headers } = request;

      headers["Authorization"] = `Bearer ${session?.user.access_token}`;

      const res = await axios.delete(process.env.BASE_URL! + url, {
        headers,
      });

      return NextResponse.json(res.data, { status: res.status });
    } catch (error: any) {
      console.error(error);

      return NextResponse.json(
        { error: error.response?.data || "Error processing request" },
        { status: error.response?.status || 500 }
      );
    }
  }
}
