import Logout from "@/components/Logout";
import UserProfile from "@/components/UserProfile";
import { isSessionValid } from "@/lib/auth";

export default async function Profile() {
  if (!(await isSessionValid())) {
    return <Logout />;
  }
  return <UserProfile />;
}
