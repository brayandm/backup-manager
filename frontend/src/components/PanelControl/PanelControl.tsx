"use client";

import { Box, Tabs, Tab, useTheme, SvgIconProps } from "@mui/material";
import { useState } from "react";

function a11yProps(index: number) {
  return {
    id: `simple-tab-${index}`,
    "aria-controls": `simple-tabpanel-${index}`,
  };
}

interface TabPanelProps {
  children?: React.ReactNode;
  index: number;
  value: number;
}

function TabPanel(props: TabPanelProps) {
  const { children, value, index, ...other } = props;

  return (
    <div
      role="tabpanel"
      hidden={value !== index}
      id={`simple-tabpanel-${index}`}
      aria-labelledby={`simple-tab-${index}`}
      {...other}
    >
      {value === index && <Box>{children}</Box>}
    </div>
  );
}
interface PanelControlProps {
  tabs: {
    icon: React.ReactElement<SvgIconProps>;
    label: string;
    component: React.ReactNode;
  }[];
}

const PanelControl = ({ tabs }: PanelControlProps) => {
  const theme = useTheme();

  const [value, setValue] = useState(0);

  const handleChange = (event: React.SyntheticEvent, newValue: number) => {
    setValue(newValue);
  };

  return (
    <>
      <Box
        sx={{
          width: "14vw",
          height: "92vh",
          backgroundColor: theme.palette.primary.dark,
        }}
      >
        <Tabs
          value={value}
          onChange={handleChange}
          orientation="vertical"
          textColor="inherit"
          sx={{
            width: "14vw",
            height: "90vh",
            padding: "2vh 0 0 0",
            "& 	.MuiTabs-indicator": {
              backgroundColor: "transparent",
            },
          }}
        >
          {tabs.map((tab, index) => (
            <Tab
              key={index}
              icon={tab.icon}
              label={tab.label}
              iconPosition="start"
              aria-label={tab.label}
              sx={{
                color: theme.palette.common.white,
              }}
              {...a11yProps(index)}
            />
          ))}
        </Tabs>
      </Box>
      {tabs.map((tab, index) => (
        <TabPanel key={index} value={value} index={index}>
          {tab.component}
        </TabPanel>
      ))}
    </>
  );
};

export default PanelControl;
