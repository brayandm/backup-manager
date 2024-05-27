import { registerUser } from "@/lib/auth";
import { NextRequest, NextResponse } from "next/server";

export async function POST(req: NextRequest) {
  const { name, email, password } = await req.json();

  if (await registerUser(name, email, password)) {
    return new NextResponse(
      JSON.stringify({
        message: "User registered successfully",
      }),
      { status: 200, headers: { "Content-Type": "application/json" } }
    );
  }

  return new NextResponse(
    JSON.stringify({
      message: "User registration failed",
    }),
    { status: 400, headers: { "Content-Type": "application/json" } }
  );
}
