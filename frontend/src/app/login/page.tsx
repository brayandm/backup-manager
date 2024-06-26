import LoginForm from "@/components/LoginForm";
import { getServerSession } from "next-auth";
import { authOptions, isFirstUse } from "@/lib/auth";
import { redirect } from "next/navigation";

export default async function Login() {
  const session = await getServerSession(authOptions);

  if (session) redirect("/dashboard");
  if (await isFirstUse()) redirect("/register");

  return <LoginForm />;
}
