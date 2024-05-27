import type { NextAuthOptions } from "next-auth";
import CredentialsProvider from "next-auth/providers/credentials";
import { Session, getServerSession } from "next-auth";
import axios from "axios";

export const authOptions: NextAuthOptions = {
  providers: [
    CredentialsProvider({
      // The name to display on the sign in form (e.g. 'Sign in with...')
      name: "Credentials",
      // The credentials is used to generate a suitable form on the sign in page.
      // You can specify whatever fields you are expecting to be submitted.
      // e.g. domain, username, password, 2FA token, etc.
      // You can pass any HTML attribute to the <input> tag through the object.
      credentials: {
        username: { label: "Username", type: "text", placeholder: "jsmith" },
        password: { label: "Password", type: "password" },
      },
      async authorize(credentials, req) {
        // You need to provide your own logic here that takes the credentials
        // submitted and returns either a object representing a user or value
        // that is false/null if the credentials are invalid.
        // e.g. return { id: 1, name: 'J Smith', email: 'jsmith@example.com' }
        // You can also use the `req` object to obtain additional parameters
        // (i.e., the request IP address)
        try {
          const res = await axios.post(
            process.env.LOGIN_URL!,
            JSON.stringify(credentials),
            {
              headers: {
                "Content-Type": "application/json",
              },
            }
          );
          const user = res.data;

          if (user["errors"]) {
            return null;
          }

          // If no error and we have user data, return it
          if (res.status == 200 && user) {
            return user;
          }
        } catch (error) {
          console.error(error);
        }
        // Return null if user data could not be retrieved
        return null;
      },
    }),
  ],
  callbacks: {
    async jwt({ token, user }) {
      user && (token.user = user);
      return token;
    },
    async session({ session, token }) {
      session.user = token.user as Session["user"];
      return session;
    },
    async redirect({ url, baseUrl }) {
      return url;
    },
  },
  events: {
    async signOut(message) {
      const access_token = (message.token.user as Session["user"]).access_token;
      try {
        await axios.post(process.env.LOGOUT_URL!, null, {
          headers: { Authorization: `Bearer ${access_token}` },
        });
      } catch (error) {
        console.error(error);
      }
    },
  },
  session: {
    maxAge: parseInt(process.env.MAX_AGE_SESSION!),
  },
  pages: {
    signIn: "/login",
  },
};

export async function isTokenExpired(session: Session) {
  const tokenExpiricyDate = session?.user.expires_in;

  return (
    tokenExpiricyDate &&
    new Date(tokenExpiricyDate).getTime() < new Date().getTime()
  );
}

export async function isTokenValidFromBackend(session: Session) {
  const access_token = session?.user.access_token;

  try {
    const res = await axios.get(process.env.VERIFY_URL!, {
      headers: { Authorization: `Bearer ${access_token}` },
    });

    return (
      res.status == 200 && res.data["message"] == "Successful verification"
    );
  } catch (error) {
    console.error(error);
    return false;
  }
}

export async function isSessionValid() {
  const session = await getServerSession(authOptions);

  if (!session) return true;

  if (!(await isTokenValidFromBackend(session))) return false;

  if (await isTokenExpired(session)) return false;

  return true;
}
