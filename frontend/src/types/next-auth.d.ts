import NextAuth from "next-auth";

declare module "next-auth" {
  interface Session {
    user: {
      access_token?: string;
      expires_in?: string;
      token_type?: string;
    };
  }
}
