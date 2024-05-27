import RegisterForm from "@/components/RegisterForm";
import { getServerSession } from "next-auth";
import { authOptions, isFirstUse } from "@/lib/auth";
import { redirect } from "next/navigation";

export default async function Register() {
  const session = await getServerSession(authOptions);

  if (session) redirect("/dashboard");
  if (!(await isFirstUse())) redirect("/login");

  return <RegisterForm />;
}
