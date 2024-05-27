import MenuNavigation from "@/components/MenuNavigation";

export default async function DashboardLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <>
      <MenuNavigation />
      {children}
    </>
  );
}
