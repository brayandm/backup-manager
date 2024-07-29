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
  value: number;
  setValue: (newValue: number) => void;
  tabs: {
    icon: React.ReactElement<SvgIconProps>;
    label: string;
    component: React.ReactNode;
  }[];
}

const PanelControl = ({ value, setValue, tabs }: PanelControlProps) => {
  const theme = useTheme();

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
                justifyContent: "flex-start",
              }}
              {...a11yProps(index)}
            />
          ))}
        </Tabs>
      </Box>
      {tabs.map((tab, index) => (
        <TabPanel key={index} value={value} index={index}>
          <div
            style={{
              height: "90vh",
              overflowY: "auto",
              overflowX: "hidden",
            }}
          >
            {tab.component}
          </div>
        </TabPanel>
      ))}
    </>
  );
};

export default PanelControl;
