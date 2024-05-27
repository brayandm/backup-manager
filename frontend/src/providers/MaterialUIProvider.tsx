"use client";

import { ThemeProvider } from "@mui/material";
import theme from "@/lib/theme";

type Props = {
  children?: React.ReactNode;
};

export const MaterialUIProvider = ({ children }: Props) => {
  return <ThemeProvider theme={theme}>{children}</ThemeProvider>;
};
