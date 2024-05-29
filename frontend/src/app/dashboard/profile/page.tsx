import Logout from "@/components/Logout";
import UserProfile from "@/components/UserProfile";
import { isSessionValid } from "@/lib/auth";
import { serverGet } from "@/lib/backendApi";

export default async function Profile() {
  const res = await serverGet("/user");

  if (!(await isSessionValid())) {
    return <Logout />;
  }
  return <UserProfile user={res.data} />;
}
