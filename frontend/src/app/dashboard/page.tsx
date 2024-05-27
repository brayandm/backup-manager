import Dashboard from "@/components/Dashboard";
import Logout from "@/components/Logout";
import { isSessionValid } from "@/lib/auth";

export default async function Home() {
  if (!(await isSessionValid())) {
    return <Logout />;
  }
  return <Dashboard />;
}
